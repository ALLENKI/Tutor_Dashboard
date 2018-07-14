<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\Models\SQL\User;
use Aham\Models\SQL\Student;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\StudentCredits;
use Aham\Models\SQL\CreditsPurchased;
use Aham\Models\SQL\CreditsHubOnly;

use Aham\Models\SQL\StudentAssessment;

use Aham\Helpers\AssessmentHelper;


use \Illuminate\Database\Eloquent\Collection;

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

    public function table1()
    {
        $statement = new Collection();

        $credits = CreditsPurchased::orderBy('added_on', 'desc')->get();
        // return $credits;
        foreach($credits as $credit)
        {
            $objectC = new \StdClass;
            // $objectC->type = 'credit';
            $objectC->id = $credit->id;
            $objectC->studentId = $credit->user->student->id;
            $objectC->name = $credit->user->name;
            $objectC->email = $credit->user->email;
            $objectC->type = 'Purchased';
            
            if(!is_null($credit->added_on)) {
                $objectC->date = $credit->added_on->format('Y-M-d');
            } else {
                $objectC->date = null;
            }

            $objectC->amount = $credit->price;
            $objectC->method = $credit->method;
            $objectC->remarks = $credit->remarks;

            if(!is_null($credit->invoice_url)) {
                 $objectC->invoice = ['invoiceNo' => $credit->invoice_no, 'url' => $credit->invoice_url];
            } else {
                $objectC->invoice = ['invoiceNo' => $credit->invoice_no, 'url' => $credit->invoice_url];
            }

            $statement->push($objectC);
        }

        $credits = CreditsHubOnly::orderBy('added_on', 'desc')->get();

        foreach ($credits as $credit) 
        {
            $objectC = new \StdClass;
            // $objectC->type = 'credit';
            $objectC->id = $credit->id;
            $objectC->studentId = $credit->user->student->id;
            $objectC->name = $credit->user->name;
            $objectC->email = $credit->user->email;
            $objectC->type = 'HubOnly';
            
            if(!is_null($credit->added_on)) {
                $objectC->date = $credit->added_on->format('Y-M-d');
            } else {
                $objectC->date = null;
            }

            

            $objectC->amount = $credit->price;
            $objectC->method = $credit->method;
            $objectC->remarks = $credit->remarks;

            if(!is_null($credit->invoice_url)) {
                $objectC->invoice = ['invoiceNo' => $credit->invoice_no, 'url' => $credit->invoice_url];
           } else {
               $objectC->invoice = ['invoiceNo' => $credit->invoice_no, 'url' => $credit->invoice_url];
           }
           
            $statement->push($objectC);
        }

        $statements = $statement;

        $credits = [];

        foreach($statements as $statement)
        {
            $credits[] = [

                'date' => $statement->date,
                'amount' => $statement->amount,
                'method' => $statement->method,
                'remarks' => $statement->remarks,
                'invoice' => $statement->invoice,
                'name' => $statement->name,
                'email' => $statement->email,
                'id' => $statement->id,
                'type' => $statement->type,
                'studentId' => $statement->studentId,

            ];
        }

        return [
            'data' => $credits,
        ];

    }

    public function destroy($id,$type)
    {

        $this->getTableData($id,$type)->delete();
    
    }
 
    public function edit($id,$type)
    {
        $data = [];

        $credit = $this->getTableData($id,$type);
        $data['name'] = $credit->user->name;
        $data['amount'] = $credit->price;
        $data['remarks'] = $credit->remarks;
        $data['method'] = $credit->method;
        $data['invoiceNo'] = $credit->invoice_no;

        return $data;
    }

    public function update(Request $request, $id,$type)
    {       
       $validation =  Validator::make($request->all(),
                        [
                            'paymentmethod' => 'required',
                            'amount' => 'required|numeric',
                            'remarks' => 'required',
                            'invoice_no' => 'required',
                        ]);

       if($validation->fails()){
           return;
       }

        $credit = $this->getTableData($id,$type);

        $credit->method = $request->input('paymentmethod');
        $credit->price = $request->input('amount');
        $credit->remarks = $request->input('remarks');
        $credit->invoice_no = $request->input('invoice_no');
        $credit->save();

    }

    public function invoice($id,$type)
    {
        // return 'invoice:- '.Input::file('invoice');

        $credit = $this->getTableData($id,$type);;

        if(Input::file('invoice') != null) {
            $this->uploadInvoice($credit);
        }

    }

    public function uploadInvoice($credit)
    {
            $invoiceNo = $credit->invoice_no;
            $formFile = Input::file('invoice');
            $extension = $formFile->getClientOriginalExtension();
            $filename = $credit->invoice_no.'-'.time().'.'.$extension;
            $upload_success = $formFile->move(storage_path('uploads'), $filename);

            $data['key'] = 'students/invoices/'.$filename;
            $data['source'] = storage_path('uploads/'.$filename);

            $result = $this->cdn->upload($data);

            $invoice = $result['url'];

            $credit->invoice_url = $invoice;
            $credit->save();

            \File::delete(storage_path('uploads/'.$filename));
    }
    

    public function getTableData($id,$type)
    {
        if($type == 'Purchased') {
            
           return CreditsPurchased::find($id);
            

        } else {
            
            return CreditsHubOnly::find($id);

        }
    }

    public function getInvoice($id,$type)
    {
        $credits = $this->getTabledata($id,$type);
        $credits->user;
        return $credits;
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

        $statement = new Collection();

        $credits = CreditsPurchased::orderBy('added_on', 'desc')->get();
        // return $credits;
        foreach($credits as $credit)
        {
            $objectC = new \StdClass;
            // $objectC->type = 'credit';
            $objectC->id = $credit->id;
            $objectC->studentId = $credit->user->student->id;
            $objectC->name = $credit->user->name;
            $objectC->email = $credit->user->email;
            $objectC->type = 'Purchased';
            
            if(!is_null($credit->added_on)) {
                $objectC->date = $credit->added_on->format('Y-M-d');
            } else {
                $objectC->date = null;
            }

            $objectC->amount = $credit->price;
            $objectC->method = $credit->method;
            $objectC->remarks = $credit->remarks;

            $objectC->invoice = $credit->invoice_no;

            $statement->push($objectC);
        }

        $credits = CreditsHubOnly::orderBy('added_on', 'desc')->get();

        foreach ($credits as $credit) 
        {
            $objectC = new \StdClass;
            // $objectC->type = 'credit';
            $objectC->id = $credit->id;
            $objectC->studentId = $credit->user->student->id;
            $objectC->name = $credit->user->name;
            $objectC->email = $credit->user->email;
            $objectC->type = 'HubOnly';
            
            if(!is_null($credit->added_on)) {
                $objectC->date = $credit->added_on->format('Y-M-d');
            } else {
                $objectC->date = null;
            }

            $objectC->amount = $credit->price;
            $objectC->method = $credit->method;
            $objectC->remarks = $credit->remarks;
            $objectC->invoice = $credit->invoice_no;
            $statement->push($objectC);
        }

        $statements = $statement;
        $statements = $statements->toArray();

        array_unshift($statements, array_keys((array) $statements[0]));

        $callback = function() use ($statements)
        {
            $FH = fopen('php://output', 'w');
            foreach ($statements as $row) {
                $row = (array) $row;

                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return \Response::stream($callback, 200, $headers);
    }

}