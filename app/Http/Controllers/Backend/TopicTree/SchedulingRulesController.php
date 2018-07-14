<?php  namespace Aham\Http\Controllers\Backend\TopicTree;

use Aham\Http\Controllers\Backend\BaseController;

use Validator;
use Input;
use Assets;
use Response;

use Aham\Models\SQL\SchedulingRule;

class SchedulingRulesController extends BaseController {

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function index()
    {
        $scheduling_rules = SchedulingRule::all();

        return view('backend.topic_tree.scheduling_rules.index',compact('scheduling_rules'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $scheduling_rule = SchedulingRule::find($id);

        return view('backend.topic_tree.scheduling_rules.edit',compact('scheduling_rule'));
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function update($id)
    {
        $rules = [
            'description' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $scheduling_rule = SchedulingRule::find($id);

        $scheduling_rule->description = Input::get('description');

        $scheduling_rule->save();

        flash()->success('Rule updated successfully');

        return redirect()->back();
    }
   

}