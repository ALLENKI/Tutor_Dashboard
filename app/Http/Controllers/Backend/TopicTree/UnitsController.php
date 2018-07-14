<?php  namespace Aham\Http\Controllers\Backend\TopicTree;

use Aham\Http\Controllers\Backend\BaseController;

use Validator;
use Input;
use Assets;
use Response;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\TopicPrerequisite;

class UnitsController extends BaseController {

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
    public function store()
    {
        $rules = [
            'name' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

    	Unit::create(Input::only('name','description','topic_id'));

        flash()->success('Unit successfully added');
    	return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
    	$unit = Unit::find($id);

    	return view('backend.topic_tree.units.edit',compact('unit'));
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
            'name' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

    	$unit = Unit::find($id);

        $unit->fill(Input::only('name','description'));

        $unit->save();

    	return redirect()->back();
    }
   
    public function updatePositions()
    {
        $parts = Input::get('unit');

        foreach($parts as $index => $part)
        {
            $postPart = Unit::find($part);

            $postPart->order = $index+1;

            $postPart->save();
        }

        return Response::json('success',200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $unit = Unit::find($id);

        $unit->delete();

        return redirect()->back();
    }

}