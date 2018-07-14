<?php  namespace Aham\Http\Controllers\Backend\TopicTree;

use Aham\Http\Controllers\Backend\BaseController;

use Aham\TDGateways\TopicGatewayInterface;

use Validator;
use Input;
use Assets;
use Response;
use DB;
use File;
use Carbon;

use Illuminate\Support\Collection;

use Aham\Models\SQL\Teacher;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\Goal;
use Aham\Models\SQL\TopicPrerequisite;
use Aham\Models\SQL\SchedulingRule;
use Aham\Models\SQL\CloudinaryImage;
use Aham\Models\SQL\TeacherCertification;

use Aham\Helpers\AssessmentHelper;
use Aham\Helpers\TreeExplorer;
use Aham\Helpers\PrerequisiteHelper;
use Aham\Helpers\TopicLookupHelper;

class TopicsController extends BaseController {

    public function __construct(TopicGatewayInterface $topicGateway)
    {
        parent::__construct();

        $this->topicGateway = $topicGateway;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

		// Assets::add("js/core/libraries/jquery_ui/core.min.js");
		// Assets::add("js/core/libraries/jquery_ui/effects.min.js");
		// Assets::add("js/core/libraries/jquery_ui/interactions.min.js");
		// Assets::add("js/plugins/extensions/cookie.js");
		// Assets::add("js/plugins/forms/styling/switchery.min.js");
		// Assets::add("js/plugins/forms/styling/uniform.min.js");
		// Assets::add("js/plugins/trees/jquery.fancytree-all.min.js");


		// Assets::add("js/plugins/visualization/d3/d3.min.js");
		// Assets::add("js/charts/d3/tree/tree_collapsible_aham.js");

        $tableRoute = route('admin::topic_tree::topics.table');

        return view('backend.topic_tree.topics.index',compact('tableRoute'));
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function indexd3()
    {
        Assets::add("js/plugins/visualization/d3/d3.min.js");
        Assets::add("js/charts/d3/tree/tree_collapsible_aham.js");

        // dd('hey');

        $query = Input::get('q','');

        return view('backend.topic_tree.topics.index_d3',compact('tableRoute','query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $topic = new Topic(['type' => Input::get('type')]);

        $parent_type = $topic->predecessor();

        $parents = Topic::where('type',$parent_type)->pluck('name','id');

        return view('backend.topic_tree.topics.create',compact('topic','parents'));
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
            'parent_id' => 'required',
            'type' => 'required|in:subject,category,sub-category,topic'
        ];

        $messages = [
            'type.in' => 'Please select a valid type.'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

    	$topic = $this->topicGateway->createTopic(Input::only('type','name','parent_id','status','minimum_enrollment','maximum_enrollment','google_slide','graph_level','notes'));

        if($topic->type == 'topic')
        {
            TopicLookupHelper::createLookup($topic);
        }
        

    	return redirect()->route('admin::topic_tree::topics.index');
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function show($id)
    {
        $topic = Topic::find($id);

        $certifiedTeachers = TeacherCertification::with('teacher.classes','teacher.user')
                                ->where('topic_id', $topic->id)
                                ->get();


        $eligibleTutors = Teacher::with('user')->whereNotIn('id',$certifiedTeachers->pluck('teacher_id')->toArray())->get();

        $eligibleTeachers = [];

        foreach($eligibleTutors as $eligibleTutor)
        {
            $eligibleTeachers[$eligibleTutor->id] = $eligibleTutor->user->name.' ('.$eligibleTutor->user->email.')';
        }

        // dd($tree);

        // $dump = file_get_contents(storage_path('uploads/dump.json'));

        // $dump = json_decode($dump, true);

        // $nodes = $dump['data']['nodes'];
        // $edges = $dump['data']['edges'];

        $exclude = array_merge($topic->prerequisites->pluck('id')->toArray(), [$topic->id]);

        // $treeExplorer = new TreeExplorer();

        // $fullTree = $treeExplorer->getFullTree([$topic->id]);

        // $fullTree = Topic::whereIn('id',$treeExplorer->explored)->get();

        $fullTree = [];

        $prerequisite_options = Topic::whereNotIn('id',$exclude)
                                        ->whereIn('type',['sub-category','topic'])
                                        ->pluck('name','id')
                                        ->toArray();

        $goals = Goal::pluck('name','id')->toArray();

        $topicClasses = $topic->classes()->whereNotIn('status',['initiated','cancelled','completed'])->get();

        return view('backend.topic_tree.topics.show',compact('topic','prerequisite_options','fullTree','goals','topicClasses','certifiedTeachers','eligibleTeachers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
    	$topic = Topic::find($id);

        $parent_type = $topic->predecessor();

    	$parents = Topic::where('id','<>',$topic->id)
                            ->where('type',$parent_type)
                            ->pluck('name','id')
                            ->toArray();

    	return view('backend.topic_tree.topics.edit',compact('topic','parents'));
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
            'type' => 'required|in:subject,category,sub-category,topic'
        ];

        $messages = [
            'type.in' => 'Please select a valid type.'
        ];

        $v = Validator::make(Input::all(), $rules, $messages);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

    	$this->topicGateway->updateTopic($id, Input::only('type','name','parent_id','description','status','minimum_enrollment','maximum_enrollment', 'google_slide', 'graph_level', 'notes'));

        $topic = Topic::find($id);

        $topic->show_on_homepage = false;

        if(Input::has('show_on_homepage'))
        {
            $topic->show_on_homepage = true;
        }

        $topic->save();

        $topic->retag(Input::get('tags'));

        if($topic->type == 'topic')
        {
            TopicLookupHelper::updateLookup($topic);
        }

    	return redirect()->back();
    }
   
    public function updatePositions()
    {
        $parts = Input::get('child');

        foreach($parts as $index => $part)
        {
            $postPart = Topic::find($part);

            $postPart->order = $index+1;

            $postPart->save();
        }

        return Response::json('success',200);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function addPrequisite($id)
    {
        $rules = [
            'topic_id' => 'required'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $topic = Topic::find(Input::get('topic_id'));
        $requirer = Topic::find($id);

        $eligible = PrerequisiteHelper::isEligible($requirer, $topic);

        // dd($eligible);

        if($eligible !== true)
        {
            flash()->error($eligible);

            return redirect()->back();
        }

        // dd('ej');

        TopicPrerequisite::create([
            'topic_id' => Input::get('topic_id'),
            'requirer_id' => $id
        ]);

        return redirect()->back();
    }

    public function removePrequisite($requirer_id,$topic_id)
    {
        TopicPrerequisite::where('requirer_id',$requirer_id)
                          ->where('topic_id',$topic_id)
                          ->first()
                          ->delete();

        flash()->success('Successfully removed prerequisite');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $topic = Topic::find($id);

        $topic->lookup()->delete();

        $topic->delete();

        return redirect()->route('admin::topic_tree::topics.index');
    }

    public function uploadDump()
    {
        $file = Input::file('file');

        $file->move(storage_path('uploads'),'TopicsDump.xls');

        return redirect()->back();
    }

    public function goals()
    {
        $topic = Topic::find(Input::get('topic_id'));

        $topic->goals()->sync(Input::get('goals',[]));

        $topic->save();

        return redirect()->back();
    }
    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function table()
    {

        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];


        $topicsModel = Topic::with('units','studentAssessments','teacherCertifications','prerequisites','parent')
                            ->where(function ($query) use ($q)  
                            {
                                $query->where('name', 'LIKE', '%'.$q.'%')
                                    ->orWhere('code', 'LIKE', '%'.$q.'%');
                            });

        // dd(Input::get('topic_type'));

        if(count(Input::get('topic_type',[])))
        {
            $topicsModel = $topicsModel->whereIn('type',Input::get('topic_type'));
        }

        $iTotalRecords = $topicsModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));

        $exportModel = clone $topicsModel;
        $exportModel = $exportModel->orderBy($column,$sort);

        $topics = $topicsModel
                        ->skip($iDisplayStart)
                        ->take($iDisplayLength)
                        ->orderBy($column,$sort)
                        ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($topics as $topic)
        {
        	$view_link = route('admin::topic_tree::topics.show',$topic->id);
            $edit_link = route('admin::topic_tree::topics.edit',$topic->id);

        	$actions = "<ul class='icons-list'>
							<li class='dropdown'>
								<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
									<i class='icon-menu9'></i>
								</a>

								<ul class='dropdown-menu dropdown-menu-right'>
									<li><a href='$view_link'><i class='icon-eye'></i> View</a></li>
									<li><a href='$edit_link'><i class='icon-pencil'></i> Edit</a></li>
								</ul>
							</li>
						</ul>";

            $row = [];

            $row['topics']['name'] = "<a href='$view_link'>".$topic->name.'</a>';
            $row['topics']['slug'] = "<a href='$view_link'>".$topic->slug.'</a>';
            $row['topics']['type'] = ucwords($topic->type);
            $row['topics']['code'] = $topic->code;
            $row['topics']['id'] = $topic->id;
            $row['topics']['created_at'] = $topic->created_at->format('jS M Y');
            $row['topics']['actions'] = $actions;

            // $row['topics']['parent_id'] = $topic->parent_id ? $topic->parent->name : 'None';
            $row['topics']['type'] = $topic->present()->typeStyled;
            $row['topics']['graph_level'] = $topic->graph_level;
            $row['topics']['status'] = $topic->present()->statusStyled;
            $row['topics']['units'] = $topic->units->count();
            $row['topics']['assessed'] = $topic->studentAssessments->count();
            $row['topics']['certified'] = $topic->teacherCertifications->count();

            $records["data"][] = $row;
        }

        if($output_mode == 'csv')
        {
            $data = [];

            $exportModel = Topic::select('id','name','type','parent_id','order', 'description','level','status','google_slide');

            foreach($exportModel->get() as $topic)
            {
                $row = [];

                $topics_data[] = $topic->toArray();
            }

            $exportModel = Unit::select('id','name','description','order','topic_id');

            foreach($exportModel->get() as $unit)
            {
                $row = [];

                $units_data[] = $unit->toArray();
            }

            $exportModel = TopicPrerequisite::select('id','topic_id','requirer_id');

            foreach($exportModel->get() as $prereq)
            {
                $row = [];

                $prereqs_data[] = $prereq->toArray();
            }

            $exportModel = SchedulingRule::select('id','no_of_units','division','days','description');

            foreach($exportModel->get() as $rule)
            {
                $row = [];

                $rules_data[] = $rule->toArray();
            }

            $exportModel = CloudinaryImage::select('id','of_id','of_type','position','public_id','format');

            foreach($exportModel->get() as $image)
            {
                $row = [];

                $images_data[] = $image->toArray();
            }

            $filename = 'TopicsDump-'.Carbon::now()->format('d-M-Y');

            \Excel::create($filename, function($excel) use($topics_data, $units_data, $prereqs_data,$rules_data,$images_data) {

                $excel->sheet('Topics', function($sheet) use($topics_data) {

                    $sheet->fromArray($topics_data);

                });

                $excel->sheet('Units', function($sheet) use($units_data) {

                    $sheet->fromArray($units_data);

                });

                $excel->sheet('Pre-Requisites', function($sheet) use($prereqs_data) {

                    $sheet->fromArray($prereqs_data);

                });

                $excel->sheet('Scheduling-Rules', function($sheet) use($rules_data) {

                    $sheet->fromArray($rules_data);

                });

                $excel->sheet('Cloudinary-Images', function($sheet) use($images_data) {

                    $sheet->fromArray($images_data);

                });

            })->export('xls');
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    public function tableTree()
    {
    	$results = [];

    	$topics = Topic::with('children')
    					->where('parent_id',Input::get('parent_id'))->get();

    	foreach($topics as $topic)
    	{
    		$result = [];

    		$result['title'] = $topic->name;
    		$result['key'] = $topic->id;
    		$result['name'] = $topic->name;

    		if($topic->children->count())
    		{
	    		$result['folder'] = true;
    			$result['lazy'] = true;
    		}


    		$results[] = $result;
    	}

    	return $results;
   }

   public function populateAllChildren($topic)
   {
        $result = [];

        $result['name'] = $topic->name.' ('.$topic->type.')';
        $result['code'] = $topic->code;
        $result['color'] = '#2196F3';
        $result['link'] = $topic->adminShowLink();

        if(!$topic->children->count())
        {

        }
        else
        {
            foreach($topic->children as $node)
            {
                $result['children'][] = $this->populateAllChildren($node);
            }
        }

        return $result;
   }

   public function populateChildren($topic, $data, $whoseChildren)
   {
        $result = [];

        switch ($topic->type) {
            case 'topic':
                if(in_array($topic->id, $data['topics']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;

                    $result['color'] = '#2196F3';
                    $result['link'] = $topic->adminShowLink();
                }

                
                if($topic->status == 'in_future' || $topic->status == 'obsolete')
                {
                    return [];
                }
            break;
            
            case 'sub-category':
                if(in_array($topic->id, $data['sub_categories']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
                    // $result['link'] = $topic->adminShowLink();
                    // $result['toggle'] = rand(0,1) ? true : false;
                }
                if(in_array($topic->id, $whoseChildren))
                {
                    $result = $this->populateAllChildren($topic);
                }
                break;
            
            case 'subject':
                if(in_array($topic->id, $data['subjects']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
                    // $result['link'] = $topic->adminShowLink();
                    // $result['toggle'] = rand(0,1) ? true : false;
                }
                if(in_array($topic->id, $whoseChildren))
                {
                    $result = $this->populateAllChildren($topic);
                }
                break;

            case 'category':
                if(in_array($topic->id, $data['categories']))
                {
                    $result['name'] = $topic->name;
                    $result['code'] = $topic->code;
                    $result['color'] = '#2196F3';
                    // $result['link'] = $topic->adminShowLink();
                    // $result['toggle'] = rand(0,1) ? true : false;
                }
                if(in_array($topic->id, $whoseChildren))
                {
                    $result = $this->populateAllChildren($topic);
                }
                break;

        }

        if(!$topic->children->count())
        {

        }
        else
        {
            foreach($topic->children as $node)
            {
            
                $children = $this->populateChildren($node, $data, $whoseChildren);
            
                if(count($children))
                {
                    $result['children'][] = $children;
                }
            }
        }

        return $result;
   }

    public function tabled3()
    {	
        $query = Input::get('q','');

    	$result = [];

    	$result['name'] = 'aham';
        $result['color'] = '#2196F3';

    	$topics = Topic::where('name','LIKE','%'.$query.'%')
                        ->get();


        $whoseChildren = [];

        if(trim($query) != '')
        {
            $whoseChildren = $topics->pluck('id')->toArray();
        }
        

        // dd($topics);

        $data['categories'] = [];
        $data['subjects'] = [];
        $data['sub_categories'] = [];
        $data['topics'] = [];

        foreach($topics as $topic)
        {

            switch ($topic->type) {
                case 'topic':
                    $data['categories'][] = $topic->parent->parent->parent->id;
                    $data['subjects'][] = $topic->parent->parent->id;
                    $data['sub_categories'][] = $topic->parent->id;
                    $data['topics'][] = $topic->id;
                    break;
                
                case 'sub-category':
                    $data['categories'][] = $topic->parent->parent->id;
                    $data['subjects'][] = $topic->parent->id;
                    $data['sub_categories'][] = $topic->id;
                    break;
                
                case 'subject':
                    $data['categories'][] = $topic->parent->id;
                    $data['subjects'][] = $topic->id;
                    break;

                case 'category':
                    $data['categories'][] = $topic->id;
                    break;

            }

        }

        foreach($data as $index => $item)
        {
            $data[$index] = array_unique($item);
        }

        $topics = Topic::with('children')
                        ->where('parent_id',0)->get();

        foreach($topics as $topic)
        {   
            $children = $this->populateChildren($topic, $data, $whoseChildren);

            if(count($children))
            {
                $result['children'][] = $children;
            }
            
        }

    	return $result;
    }

    public function topicTree()
    {
        $topicTree = [];

        $topics = Topic::topic()->get();

        foreach($topics as $topic)
        {
           $tree = [];

           $tree['topic'] = $topic->toArray();
           $tree['sub-category'] = $topic->parent->toArray();

           $tree['subject'] = $topic->parent->parent->toArray();
           $tree['category'] = $topic->parent->parent->parent->toArray();

           $tree['prerequisites'] = $topic->prerequisites;

           $topicTree[$topic->id] = $tree;
        }

        return $topicTree;
    }

    public function certifyTeacher($id)
    {
        foreach(Input::get('teacher_id',[]) as $teacher)
        {
            AssessmentHelper::addTeacherCertification($teacher, $id);
        }

        flash()->success('Successfully added certification.');

        return redirect()->back();   

    }
}