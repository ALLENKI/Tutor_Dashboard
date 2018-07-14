use Aham\CourseCatalog\NeoHelper;
use Everyman\Neo4j;
use Everyman\Neo4j\Cypher;

use Aham\CourseCatalog\CategoryHelper;

Route::get('build', function(){
$page = "
<h3> category <h3>
<a href=".route("create-category")."> create category </a> <br>
<a href=".route("get-category")."> get category </a> <br>
<a href=".route("delete-category")."> delete category </a> <br>
<a href=".route("update-category")."> update category </a> <br>
<a href=".route("category-relationship")."> category-relationship category </a> <br>
<h3> subject </h3>
<a href=".route("create-Subject")."> create-subject category </a> <br>
<a href=".route("delete-Subject")."> delete-Subject category </a> <br>
";
echo $page;
});

Route::get('create-category',function(){
$category = new CategoryHelper();

    $category->create(['title' => 'newCategory','testKey' => 'testValue','key1' => 'value1','test1' => 'test2','id' => 6]);

})->name('create-category');

Route::get('get-category',function(){
$category = new CategoryHelper();

    $category->get(['title' => 'science','id' => 2]);

})->name('get-category');

Route::get('delete-category',function(){
$category = new CategoryHelper();

    $category->delete(['id' => 6]);

})->name('delete-category');

Route::get('update-category',function(){
$category = new CategoryHelper();

    $matchArray = ['id' => '6'];
    $update = ['title' => 'updated','testKey' => 'updated','key1' => 'value1','test1123' => 'updated','id' => 6];
    $category->update($matchArray,$update);

})->name('update-category');

Route::get('category-relationship',function(){
$category = new CourseCatalogHelper();

    $categoryProperties = ['id' => 2];
    $subjectProperites = ['id' => 1];
    $category->addHasRelationToSubject($categoryProperties,$subjectProperites);

})->name('category-relationship');





# trying to create a circular dependency 
match (a:Topic {id:90})
create(b:Topic {id: 91,name: "B"})
create(c:Topic {id: 92,name: "C"})
create(d:Topic {id: 93,name: "1"})
create(e:Topic {id: 94,name: "2"})
create (a)-[r:HAS]->(b),(b)-[r1:HAS]->(d),(b)-[r2:HAS]->(e),(a)-[r3:HAS]->(c)
return a,b,c,d,e,r,r1,r2,r3;

create(b:Topic {id: 100,name: "1"})
create(c:Topic {id: 101,name: "2"})
create(d:Topic {id: 102,name: "3"})
create(e:Topic {id: 103,name: "4"})
create (b)-[r1:HAS]->(c),(c)-[r2:HAS]->(e),(b)-[r3:HAS]->(d)
return b,c,d,e,r1,r2,r3;
