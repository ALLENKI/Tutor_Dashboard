<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\StudentCredits;

use Aham\Models\SQL\StudentAssessment;

use Aham\Helpers\AssessmentHelper;

use Input;
use Sentinel;
use Validator;
use DB;
use Assets;
use Redirect;
use Session;

use Aham\Helpers\GraphHelper;


use Aham\Services\Storage\CDNInterface;

class PaymentsController extends BaseController
{

    public function __construct(CDNInterface $cdn)
    {
        parent::__construct();

        $this->cdn = $cdn;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tableRoute = route('admin::users::payments.table');

        return view('backend.users.payments.index',compact('tableRoute'));
    }

    public function invoiceView()
    {

    }

    public function export()
    {
        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=Payments_'.\Carbon::now()->format('d-m-Y_h:i').'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $rows = \DB::table('student_credits')
            ->join('students', 'student_credits.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select(\DB::raw("student_credits.id as ID"),DB::raw("users.name as Student"),DB::raw("users.email as Email"),\DB::raw("students.code as Code"), \DB::raw("student_credits.created_at as Date"),\DB::raw("student_credits.invoice_no as Invoice_No"), \DB::raw("student_credits.amount_paid as Amount"), \DB::raw("student_credits.method as Method"),\DB::raw("student_credits.remarks as Remarks") )
            ->where('student_credits.amount_paid','>',0)
            ->get()
            ->toArray();

        // dd($rows);
       array_unshift($rows, array_keys((array) $rows[0]));
        // dd($rows);
       $callback = function() use ($rows)
        {
            $FH = fopen('php://output', 'w');
            foreach ($rows as $row) {
                $row = (array) $row;
                dd($row);

                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return \Response::stream($callback, 200, $headers);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $rules = [
            'user_id' => 'required|exists:users,id'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $user = User::find(Input::get('user_id'));

        if($user->student)
        {
            flash()->error('This user already is a student');
        }

        $student = new Student();

        $user->student()->save($student);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $studentPaymentId =  StudentCredits::find($id);
        $method = $studentPaymentId->method;
        $amount = $studentPaymentId->amount_paid;
        $remarks = $studentPaymentId->remarks;
        $invoiceNo = $studentPaymentId->invoice_no;

        return view('backend.users.payments.edit',compact('studentPaymentId','method','amount','remarks','invoiceNo'));
    }

    /**
     * Update the specified resource in storage.
     * PUT request form payments
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

       $validation =  Validator::make($request->all(),
                        [
                            'paymentmethod' => 'required',
                            'amount' => 'required|numeric',
                            'remarks' => 'required',
                            'invoice_no' => 'required',
                        ]);

       if($validation->fails()){
           Session::flash('message', "all the input fields need to be updated/required, Try again");
           return Redirect::back()->withErrors($validation)->withInput();
       }

        $student = StudentCredits::find($id);

        $student->method = $request->input('paymentmethod');
        $student->amount_paid = $request->input('amount');
        $student->remarks = $request->input('remarks');
        $student->invoice_no = $request->input('invoice_no');
        $student->save();


        if(Input::file('invoice') != null){
            $invoiceNo = $student->invoice_no;
            $formFile = Input::file('invoice');
            $extension = $formFile->getClientOriginalExtension();
            $filename = $student->invoice_no.'-'.time().'.'.$extension;
            $upload_success = $formFile->move(storage_path('uploads'), $filename);

            $data['key'] = 'students/invoices/'.$filename;
            $data['source'] = storage_path('uploads/'.$filename);

            $result = $this->cdn->upload($data);

            $invoice = $result['url'];

            $student->invoice_url = $invoice;
            $student->save();

            \File::delete(storage_path('uploads/'.$filename));
        }

        return redirect()->route('admin::users::payments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        StudentCredits::find($id)->delete();

        flash()->success('Deleted Successful');
        return redirect()->route('admin::users::payments.index');
    }


    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        // dd($q);

        $model = StudentCredits::with('student.user')
                        ->where(function ($query) use ($q) {

                            $query
                            ->where('invoice_no', 'LIKE', '%'.$q.'%')
                            ->orWhere('invoice_no','=',NULL)
                            ->orWhereHas('student.user', function($query) use ($q)
                            {
                                $query->where(function ($query) use ($q)
                                {
                                    $query->where('name', 'LIKE', '%'.$q.'%')
                                        ->orWhere('email', 'LIKE', '%'.$q.'%');
                                });

                            });

                        });
                        //->where('amount_paid','>',0);

        $model = $model->where('amount_paid','<>',0);

         //dd($model);

        if(count(Input::get('method')))
        {
            $model = $model->whereIn('method',Input::get('method'));
        }


        $iTotalRecords = $model->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));

        $payments = $model
                    // ->skip($iDisplayStart)
                    // ->take($iDisplayLength)
                    // ->orderBy($column,$sort)
                    ->get();

        $records = array();
        $records["data"] = array();

        // dd($students);
        foreach($payments as $payment)
        {
            $user_link = route('admin::users::users.show',$payment->student->user->id);
            //$invoice_link = route('invoice',$payment->invoice_no);
            $invoice_link = $payment->invoice_url;

            $view_link = route('admin::users::payments.edit',$payment->id);

            $actions = "<a href='$view_link'><i class='icon-pencil'></i> </a>";

            $row = [];

            $row['payments']['id'] = $payment->id;
            $row['payments']['created_at'] = $payment->created_at->format('d-M-Y');
            $row['payments']['amount_paid'] = $payment->amount_paid;
            $row['payments']['invoice_no'] = "<a href='$invoice_link' target='_blank'>".$payment->invoice_no;
            $row['payments']['email'] = "<a href='$user_link'>".$payment->student->user->email."</a>";
            $row['payments']['student_id'] = $payment->student->user->name;
            $row['payments']['method'] = $payment->method;
            $row['payments']['remarks'] = $payment->remarks;
            $row['payments']['actions'] = $actions;

            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }
}
