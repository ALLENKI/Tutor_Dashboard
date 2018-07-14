<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aham\Repositories\SubCategoryRepository;

class SubCategoriesController extends Controller
{
    private $subCategory;

    public function __construct(SubCategoryRepository $subCategory)
    {
        $this->subCategory = new SubCategoryRepository;
    }

    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
        if ($subCategory = $this->subCategory->createSubCategoryWithNeo4J($request->only('name', 'parent_id', 'description', 'topics','hub'))) {
            return response()->json([
                'success' => true,
                'subCategory' => $subCategory
            ], 200);
        }

        return  response()->json(['error' => true], 200);

        if ($request->has('prerequisites')) {
            if ($this->subCategory->createNewSubCategoryWithNeo4J($request->only('name', 'parent_id'))) {
                return response()->json(['success' => true], 200);
            }
        }

        return  response()->json(['error' => true], 200);
    }

    public function show($id)
    {
        $subCategory = $this->subCategory->makeModel()->with('subject')->find($id);

        $result = [];
        $result['id'] = $subCategory->id;
        $result['name'] = $subCategory->name;
        $result['description'] = $subCategory->description;
        $result['category_id'] = $subCategory->subject->parent_id;
        $result['category_name'] = $subCategory->subject->category->name;
        $result['subject_id'] = $subCategory->parent_id;
        $result['subject_name'] = $subCategory->subject->name;

        return $result;
    }

    public function update($id, Request $request)
    {
        if ($subCategory = $this->subCategory->updateSubCategoryWithNeo4j($id, $request->only('name', 'parent_id', 'description','topics'))) {
            return response()->json([
                'success' => true,
                'subcategory' => $subCategory
            ], 200);
        }

        return response()->json(['error' => true], 200);
    }

    public function destroy($id)
    {
        if ($this->subCategory->deleteSubCategoryWithNeo4j($id)) {
            return response()->json(['success' => true], 200);
        }

        return response()->json(['errror' => true], 200);
    }
}
