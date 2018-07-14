<?php

namespace Aham\Http\Controllers\V2\Common;

use Aham\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aham\Repositories\CategoryRepository;
use GraphAware\Neo4j\Client\ClientBuilder;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Course;
use Input;

class CategoriesController extends Controller
{
    private $category;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
    }

    public function categoryTree(Request $request)
    {
        $categories = $this->category->findWhere(['type' => 'category'])
                            ->all();

        $categoryTree = [];

        foreach ($categories as $category) {
            $categoryBranch = [];
            $categoryBranch['value'] = $category->id;
            $categoryBranch['label'] = $category->name;
            $categoryTree[] = $categoryBranch;
        }

        return $categoryTree;
    }

    public function subjectTree(Request $request)
    {
        $categories = $this->category->with(['children'])
                            ->findWhere(['type' => 'category'])
                            ->all();

        $subjectTree = [];

        foreach ($categories as $category) {
            $subjectBranch = [];
            $subjectBranch['value'] = $category->id;
            $subjectBranch['label'] = $category->name;
            $subjectBranch['children'] = $this->children($category);
            $subjectTree[] = $subjectBranch;
        }

        return $subjectTree;
    }

    public function children($topic, $children = false, $deep = false)
    {
        $topicChildren = [];
        foreach ($topic->children as $child) {
            $topicChild = [];
            $topicChild['value'] = $child->id;
            $topicChild['label'] = $child->name;
            $topicChild['status'] = $child->status;
            if ($children) {
                $topicChild['children'] = $this->children($child, $deep);
            }
            if ($child->units->count()) {
                $topicChild['units'] = $child->units->count();
                $topicChild['label'] .= ' - '.$topicChild['units'].' Units';
            }
            $topicChildren[] = $topicChild;
        }

        return $topicChildren;
    }

    public function subCategoryTree(Request $request)
    {
        $categories = $this->category->with(['children.children'])
                            ->findWhere(['type' => 'category'])
                            ->all();

        $subCategoryTree = [];

        foreach ($categories as $category) {
            $subjectBranch = [];
            $subjectBranch['value'] = $category->id;
            $subjectBranch['label'] = $category->name;
            $subjectBranch['children'] = $this->children($category, true);
            $subCategoryTree[] = $subjectBranch;
        }

        return $subCategoryTree;
    }


    public function neoTopicTree(Request $request)
    {
        $client = ClientBuilder::create()
            ->addConnection('default', 'https://'.env('NEO_USERNAME').':'.env('NEO_PASSWORD').'@'.env('NEO_HOST').':'.env('NEO_PORT'))
            ->build();

        $nodes = [];

        $query = "MATCH p=(a)-[:HAS*0..3]->(m)-[:HAS*0..3]->(n)
                WHERE m.id = 8 OR m.id = 7 OR m.id = 43
                WITH COLLECT(p) AS ps
                CALL apoc.convert.toTree(ps) yield value
                RETURN value;";

        foreach($client->run($query)->getRecords() as $record)
        {
            $values = $record->values()[0];

            if($values['_type'] == 'Subject')
            {
                $nodes[] = $values;
            }
        }

        $topicsModel = $this->category->makeModel()
                            ->with('units');

        if($request->has('units'))
        {
            $topicsModel = $topicsModel->has('units','=',$request->get('units'));
        }

        $topics = $topicsModel
                            ->where(['type' => 'topic'])
                            ->get();

        return ['tree' => $nodes, 'list' => $topics];
    }

    public function topicTree(Request $request)
    {
        $categories = $this->category->with(['children.children'])
                            ->findWhere(['type' => 'subject'])
                            ->all();

        $topicTree = [];

        foreach ($categories as $category) {
            $subjectBranch = [];
            $subjectBranch['value'] = $category->id;
            $subjectBranch['label'] = $category->name;
            $subjectBranch['status'] = $category->status;
            $subjectBranch['children'] = $this->children($category, true);
            $topicTree[] = $subjectBranch;
        }

        $topicsModel = $this->category->makeModel()
                            ->with('units');

        if($request->has('units'))
        {
            $topicsModel = $topicsModel->has('units','=',$request->get('units'));
        }

        $topics = $topicsModel
                            ->where(['type' => 'topic'])
                            ->get();

        return ['tree' => $topicTree, 'list' => $topics];
    }

    public function courseTree(Request $request)
    {
        return Course::where('type','collection_of_topics')->get();
    }

    public function index(Request $request)
    {
    }

    public function show($id)
    {
        $category = $this->category->makeModel()->with('children')->find($id);

        // dd($category);

        return $category;
    }

    public function store(Request $request)
    {
        if ($category = $this->category->createNewCategoryWithNeo4J($request->only('name', 'description','hub'))) {
            return response()->json([
                'success' => true,
                'category' => $category
            ], 200);
        }
        return  response()->json(['error' => true], 200);
    }

    public function update($id, Request $request)
    {
        if ($category = $this->category->updateCategoryWithNeo4j($id, $request->only('name', 'description'))) {
            return response()->json([
                'success' => true,
                'category' => $category
            ], 200);
        }

        return response()->json(['error' => true], 200);
    }

    public function destroy($id)
    {
        if ($this->category->deleteCategoryWithNeo4j($id)) {
            return response()->json(['success' => true], 200);
        }

        return response()->json(['errror' => true], 200);
    }
}
