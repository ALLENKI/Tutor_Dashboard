<?php  namespace Aham\Http\Controllers\Backend\TopicTree;

use Aham\Http\Controllers\Backend\BaseController;

use Validator;
use Input;
use Assets;
use Response;
use DB;

use Aham\Models\SQL\Goal;

class GoalsController extends BaseController {

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
        $tableRoute = route('admin::topic_tree::goals.table');

        return view('backend.topic_tree.goals.index',compact('tableRoute'));
    }

    public function store()
    {
        $rules = [
            'name' => 'required|unique:goals,name',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        Goal::create(Input::only('name','description'));

        flash()->success('Goal created');

        return redirect()->route('admin::topic_tree::goals.index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $goal = Goal::find($id);
        $goal_topics = $goal->topics->pluck('id')->toArray();

        $added_ids = [];
        $added_edges = [];
        $nodes = [];
        $egdes = [];

        $level_processed_nodes = [];

        $levels = [];

        $node = [];

        $node['id'] = 0;
        $node['label'] = $goal->name;
        $node['title'] = $goal->name;
        $node['mass'] = 10;
        $node['color'] = 'green';
        $node['level'] = 0;

        $nodes[] = $node;
        // d($goal_topics);

        foreach($goal->topics as $topic)
        {
            // d('Get Flow: '.$topic->id);

            $rows = DB::select('CALL TOPIC_FLOW(?)',[$topic->id]);

            // If these children are in goal_topics array - delete!

            foreach($rows as $row)
            {
                if(($key = array_search($row->DID, $goal_topics)) !== false) {
                    unset($goal_topics[$key]);
                }

                // ddd($row);

                if(!in_array($row->ID, $added_ids))
                {

                    $added_ids[] = $row->ID;

                    $node = [];

                    $node['id'] = $row->ID;
                    $node['label'] = $row->NAME;
                    $node['title'] = $row->NAME;
                    // $node['mass'] = in_array($row->ID, $goal_topics) ? 20 : 10;
                    $node['mass'] = 30;
                    $node['level'] = $row->LEVEL;

                    if(is_null($row->LEVEL))
                    {
                        $node['level'] = 6;
                    }
                    else
                    {
                        $node['level'] = $row->LEVEL;
                    }

                    // $node['color'] = in_array($row->ID, $goal_topics) ? '#f98507' : '#97c2fc';
                    $node['color'] = ['background' => '#97c2fc','highlight' => [
                    'background' => '#861aaf']];

                    $nodes[] = $node;
                }

                if(!is_null($row->DID))
                {   
                    $edge = [];
                    $edge['from'] = $row->ID;
                    $edge['to'] = $row->DID;

                    $edge['arrows'] = 'from';

                    $edge_code = $row->ID."-".$row->DID;

                    if(!in_array($edge_code, $added_edges))
                    {
                        $added_edges[] = $edge_code;
                        $edges[] = $edge;
                    }
                    
                }

                
            }

            // d($goal_topics);
        }

        // dd($goal_topics);

        foreach($goal_topics as $goal_topic)
        {
            // d($goal_topic);

            $edge = [];
            $edge['from'] = 0;
            $edge['to'] = $goal_topic;
            $edge['arrows'] = 'from';

            $edge_code = "0-".$goal_topic;
            $added_edges[] = $edge_code;


            $edges[] = $edge;

            foreach($nodes as $index => $node)
            {
                if($node['id'] == $goal_topic)
                {
                   $node['mass'] = 20;
                   // $node['color'] = '#f98507';
                   // unset($nodes[$index]);
                   $nodes[$index] = $node;
                }
            }
        }

        // dd($nodes);

        // d($level_processed_nodes, $levels);

        // dd($goal->topics->pluck('name','id'));

        return view('backend.topic_tree.goals.show',compact('goal','nodes','edges'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $goal = Goal::find($id);

        return view('backend.topic_tree.goals.edit',compact('goal'));
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
            'name' => 'required|unique:goals,name,'.$id,
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $goal = Goal::find($id);

        $goal->name = Input::get('name');
        $goal->description = Input::get('description');
        $goal->active = false;

        if(Input::has('active'))
        {
            $goal->active = true;
        }

        $goal->save();

        flash()->success('Goal updated successfully');

        return redirect()->route('admin::topic_tree::goals.index');
    }
   
    public function destroy($id)
    {
        $goal = Goal::find($id);

        $goal->delete();

        flash()->success('Goal deleted successfully');

        return redirect()->route('admin::topic_tree::goals.index');
    }

    public function table()
    {
        $output_mode = Input::get('o','json');

        $q = Input::get('search')['value'];

        $column = Input::get('order')[0]['column'];

        $sort = Input::get('order')[0]['dir'];

        $column = Input::get('columns')[$column]['name'];

        $goalsModel = Goal::where('name', 'LIKE', '%'.$q.'%');

        $iTotalRecords = $goalsModel->count();
        $iDisplayLength = intval(Input::get('length',10));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval(Input::get('start',0));
        $sEcho = intval(Input::get('draw',1));

        $goals = $goalsModel
                        ->skip($iDisplayStart)
                        ->take($iDisplayLength)
                        ->orderBy($column,$sort)
                        ->get();

        $records = array();
        $records["data"] = array(); 

        foreach($goals as $goal)
        {
            $view_link = route('admin::topic_tree::goals.show',$goal->id);
            $edit_link = route('admin::topic_tree::goals.edit',$goal->id);

            $actions = "<ul class='icons-list'>
                            <li class='dropdown'>
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                    <i class='icon-menu9'></i>
                                </a>
                                <ul class='dropdown-menu dropdown-menu-right'>
                                    <li><a href='$edit_link'><i class='icon-pencil'></i> Edit</a></li>
                                </ul>
                            </li>
                        </ul>";

            $row = [];

            $row['goals']['id'] = $goal->id;

            $row['goals']['active'] = 'No'; 

            if($goal->active)
            {
              $row['goals']['active'] = 'Yes';  
            }
            
            $row['goals']['name'] = "<a href='$view_link'>".$goal->name.'</a>';
            $row['goals']['actions'] = $actions;

            $records["data"][] = $row;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

}