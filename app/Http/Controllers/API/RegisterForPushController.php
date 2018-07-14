<?php

namespace Aham\Http\Controllers\API;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;

use Aham\Transformers\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;

use Validator;
use Input;
use Sentinel;
use League\Fractal;

use Aham\Models\SQL\CloudMsgId;
use Aham\Models\SQL\ClassInvitation;
use Aham\Models\SQL\AhamClass;

class RegisterForPushController extends Controller
{
    use Helpers;

    public function register()
    {
    	$user = $this->auth->user();

        $rules = [
            'device_id' => 'required',
            'push_id' => 'required',
            'type' => 'required',
            'source' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return $this->response->withArray([
                    'result'=>'error',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(400);
        }

        \Log::info(Input::all());

        $pushReg = CloudMsgId::firstOrCreate([
            'device_id' => Input::get('device_id'),
            'source' => Input::get('source'),
            'type' => Input::get('type')
        ]);

        $pushReg->push_id = Input::get('push_id');
        $pushReg->user_id = $user->id;
        $pushReg->save();

        return $this->response->withArray([
            'result'=>'success'
        ])->setStatusCode(200);

    }

    public function testInvitePush($id)
    {
        $invite = ClassInvitation::find($id);

        $this->dispatch(new \Aham\Jobs\Teacher\SendInvitedPushNotification($invite));

        return $this->response->withArray([
            'result'=>'success'
        ])->setStatusCode(200);
    }

    public function testClassPush($id)
    {
        $ahamClass = AhamClass::find($id);

        $this->dispatch(new \Aham\Jobs\Teacher\SendClassPushNotification($ahamClass));

        return $this->response->withArray([
            'result'=>'success'
        ])->setStatusCode(200);
    }


    public function delete()
    {
        $user = $this->auth->user();

        $rules = [
            'device_id' => 'required',
            'type' => 'required',
            'source' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return $this->response->withArray([
                    'result'=>'error',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(400);
        }

        \Log::info(Input::all());

        $pushReg = CloudMsgId::firstOrCreate([
            'device_id' => Input::get('device_id'),
            'source' => Input::get('source'),
            'type' => Input::get('type')
        ]);

        if(!is_null($pushReg))
        {
            $pushReg->delete();
        }

        return $this->response->withArray([
            'result'=>'success'
        ])->setStatusCode(200);

    }
    
}
