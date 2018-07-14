<?php

namespace Aham\Http\Controllers\Backend\Users;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;
use Aham\TDGateways\UserGatewayInterface;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\Models\SQL\BetApplicant;


use Input;
use Sentinel;
use Activation;
use Validator;

class BetApplicantsController extends BaseController 
{

    public function __construct(UserGatewayInterface $userGateway)
    {
        parent::__construct();

        $this->userGateway = $userGateway;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tableRoute = route('admin::users::bet_applicants.table');

        return view('backend.bet_applicants',compact('tableRoute'));
    }

  
    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $betApplicantsModel = BetApplicant::where('email', 'LIKE', '%'.$q.'%')
                                ->orWhere('full_name', 'LIKE', '%'.$q.'%');

        $iTotalRecords = $betApplicantsModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));


        $bet_applicants = $betApplicantsModel
                        ->skip($iDisplayStart)
                        ->take($iDisplayLength)
                        ->orderBy($column,$sort)
                        ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($bet_applicants as $bet_applicant)
        {
            $row['bet_applicants']['full_name'] = $bet_applicant->full_name;
            $row['bet_applicants']['age'] = $bet_applicant->age;
            $row['bet_applicants']['school'] = $bet_applicant->school;
            $row['bet_applicants']['email'] = $bet_applicant->email;
            $row['bet_applicants']['mobile'] = $bet_applicant->mobile;
            $row['bet_applicants']['address'] = $bet_applicant->address;
            $row['bet_applicants']['other_programs'] = $bet_applicant->other_programs;
            $row['bet_applicants']['programming_exp'] = $bet_applicant->programming_exp;
            $row['bet_applicants']['business_vertical'] = $bet_applicant->business_vertical;
            $row['bet_applicants']['summer_exp'] = $bet_applicant->summer_exp;
            $row['bet_applicants']['fav_books'] = $bet_applicant->fav_books;
            $row['bet_applicants']['challenge'] = $bet_applicant->challenge;

            $records["data"][] = $row;
        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    
    public function export()
    {
        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=BetApplicants_'.\Carbon::now()->format('d-m-Y_h:i').'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $rows = \DB::table('bet_applicants')
            
            ->select(\DB::raw("bet_applicants.full_name as Name"), \DB::raw("bet_applicants.age as Age"), \DB::raw("bet_applicants.school as School"), \DB::raw("bet_applicants.email as Email"), \DB::raw("bet_applicants.mobile as Mobile"), \DB::raw("bet_applicants.address as Address"), \DB::raw("bet_applicants.other_programs as Other_Programs"), \DB::raw("bet_applicants.programming_exp as Programming_Experience"), \DB::raw("bet_applicants.business_vertical as Business_Vertical"), \DB::raw("bet_applicants.summer_exp as Summer_Exp"), \DB::raw("bet_applicants.fav_books as Fav_Books_Authors"), \DB::raw("bet_applicants.challenge as Challenges") )->get();


       array_unshift($rows, array_keys((array) $rows[0]));

       $callback = function() use ($rows) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($rows as $row) {
                $row = (array) $row;

                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return \Response::stream($callback, 200, $headers);
    }

}
