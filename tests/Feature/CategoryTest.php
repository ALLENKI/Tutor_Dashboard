<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Aham\CourseCatalog\CategoryHelper;
use Aham\CourseCatalog\NeoHelper;

class CategoryTest extends TestCase
{
    protected $category;
    protected $categoryProperties;
    protected $id;
    protected $updateValues;

    protected function setUp()
    {
       
        $this->id =  20;
        $this->categoryProperties = [
                                        'id' => $this->id,
                                        'title' => 'science',
                                        'key1' => 'value1',
                                    ];

        $this->updateValues = [
                                'id' => $this->id,
                                'title' => 'science',
                                'key1' => 'value1',
                              ];
    }

    public function testNeoConnection(){
        $this->neoHelper = new NeoHelper();

        $this->neoHelper->getNode('category',['id' => 6]);
    }

    public function testCreateCategoryAndGetCategory()
    {
        $this->category = new CategoryHelper();
        // create category
        $this->category->create($this->categoryProperties);

        // get category
        $data = $this->category->get(['id' => $this->id]);
        
        $this->assertGreaterThan(0,count($data));
    }

    public function testUpdateCategory()
    {        
        $matchArray = ['id' => $this->id];
        $this->category->update($matchArray,$this->updateValues);

        $data = $this->category->get(['id' => $this->id]);

        // dd($data,$updateArray);

        $this->assertNotEquals($data,$this->updateValues);
    }

    public function testDeleteCategory()
    {
        $this->category->delete(['id' => $this->id]);
        $data = $this->category->get(['id' => $this->id]);
        
        $this->assertEquals(0,count($data));
    }

    public function testRelationship()
    {
        $this->category->relationship();
    }
}
