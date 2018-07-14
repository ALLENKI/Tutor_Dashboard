<?php
namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Aham\Repositories\CourseRepository;
use Aham\Helpers\CreditsHelper;
use Illuminate\Http\Request;

class CreditsController extends Controller
{
    protected $credits;
    public function __construct(CourseRepository $courseRepository)
    {
        $this->credits = new CreditsHelper;
    }

    public function index(Request $request)
    {
         
    }

    public function buy(Request $request)
    {
         $this->credits->deduct($request->only('credits','unit_id','hub_id','user_id'));
    }

    public function store(Request $request)
    {   
        // repository helper
        if($this->credits->add($request->only('credits','type','price','hub_id','user_id','coupon'))) {
             return response()->json(['success' => true],200);
        } 

        return  response()->json(['error' => true],200);
    }

    public function update($id,Request $request)
    {
       
        if(false){
         return response()->json(['success' => true],200);
        }
        
        return response()->json(['error' => true],200);
    }

    public function destroy($id)
    {
        if(false){
             return response()->json(['success' => true],200);
        }
        
        return response()->json(['errror' => true],200);
    }

}