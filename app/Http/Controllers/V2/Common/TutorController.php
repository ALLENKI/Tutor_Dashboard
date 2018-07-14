<?php

namespace Aham\Http\Controllers\V2\Common;

use Aham\Http\Controllers\Controller;
use Aham\Repositories\TutorRepository;
use Aham\TransformersV2\TutorTransformer;
use Aham\CreditsEngine\Sync;

use Aham\Models\SQL\Location;
use Aham\Models\SQL\Teacher;

use Input;

use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;


class TutorController extends BaseController
{

    private $tutor;

    public function __construct(TutorRepository $tutor)
    {
        $this->tutor = $tutor;
    }

    public function index()
    {
        $filters = [];
        
        $model = $this->tutor->filter(
            request()->get('input', ''),
            request()->get('sort', 'created_at_desc'),
            $filters
        );

        $paginator = $model->paginate(20);

        $topics = $paginator->getCollection();

        $resource = new Fractal\Resource\Collection($topics, new TutorTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($resource)->toArray();
    }

    public function dataTable() {

        $q = Input::get('search')['value'];

        // dd($q);
        $model = Teacher::with('user')
                        ->where(function ($query) use ($q) {
                                    
                            $query
                            ->where('code', 'LIKE', '%'.$q.'%')
                            ->orWhereHas('user', function($query) use ($q)
                            {
                                $query->where(function ($query) use ($q)
                                {
                                    $query->where('name', 'LIKE', '%'.$q.'%')
                                        ->orWhere('email', 'LIKE', '%'.$q.'%');
                                });
                                
                            });

                        });

        if(Input::has('active')) {
            if( Input::get('active') === 'true' ) {
                $model =  $model->where('active', true);
            } else {
                $model =  $model->where('active', false);
            }
        }

        $iTotalRecords = $model->count();

        $teachers = $model->get();

        $records = array();
        $records["data"] = array(); 

        //$row['teachers']['active'] = $teacher->active ? 'yes' : 'no';
        foreach($teachers as $teacher)
        {
            $row = [];
            $id = $teacher->id;

            $row['code'] = "<a href='admin-db#/user-permission/$id'>".$teacher->code."</a>";
            $row['name'] = $teacher->user->name;
            $row['email'] = $teacher->user->email;
            $row['status'] = $teacher->active;
            $records["data"][] = $row;
        }

        $records["recordsTotal"] = $iTotalRecords;

        return $records;
    }

    public function getTutor($id) {
        
        $tutor =  Teacher::find($id);

        return $this->response->item($tutor, new TutorTransformer);
    }

}