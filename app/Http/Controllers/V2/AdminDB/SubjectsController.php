<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aham\Repositories\SubjectRepository;

class SubjectsController extends Controller
{
    private $subject;

    public function __construct(SubjectRepository $subject)
    {
        $this->subject = new $subject;
    }

    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
        if ($subject = $this->subject->createNewSubjectWithNeo4J($request->only('name', 'description', 'parent_id','hub_id'))) {
            return response()->json([
                'success' => true,
                'subject' => $subject
            ], 200);
        }

        return  response()->json(['error' => true], 200);
    }

    public function show($id)
    {
        $subject = $this->subject->makeModel()->with('category')->find($id);

        $result = [];
        $result['id'] = $subject->id;
        $result['name'] = $subject->name;
        $result['description'] = $subject->description;
        $result['category_id'] = $subject->parent_id;
        $result['category_name'] = $subject->category->name;
        $result['visibility'] = (bool) $subject->visibility;

        return $result;
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        if ($subject = $this->subject->updateSubjectWithNeo4j($id, $request->only('name', 'description', 'parent_id','visibility'))) {
            return response()->json([
                'success' => true,
                'subject' => $subject
            ], 200);
        }

        return  response()->json(['error' => true], 200);
    }

    public function destroy($id)
    {
        if ($this->subject->deleteSubjectWithNeo4j($id)) {
            return response()->json(['success' => true], 200);
        }

        return response()->json(['errror' => true], 200);
    }
}
