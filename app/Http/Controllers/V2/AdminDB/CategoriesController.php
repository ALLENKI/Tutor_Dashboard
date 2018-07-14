<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aham\Repositories\CategoryRepository;

class CategoriesController extends Controller
{
    private $category;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
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
        if ($category = $this->category->createNewCategoryWithNeo4J($request->only('name', 'description','hub_id','approve'))) {
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
