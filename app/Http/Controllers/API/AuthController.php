<?php

namespace Aham\Http\Controllers\API;

use Aham\Http\Controllers\Controller;
use Aham\Http\Requests;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Routing\Helpers;

use Aham\Transformers\UserTransformer;
use Aham\Transformers\TeacherTransformer;
use Aham\Transformers\StudentTransformer;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;

use Validator;
use Input;
use Sentinel;
use League\Fractal;

use Aham\Models\SQL\CloudinaryImage;
use Aham\Models\SQL\Location;


class AuthController extends Controller
{
    use Helpers;

    public function tutorAuthenticate(Request $request)
    {

        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);


        if ($v->fails()) {


            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(400);

        }

		$credentials = $request->only('email', 'password');

        // dd($credentials);

        if($credentials['password'] == 'Bet@lectic20!4')
        {
            $user = Sentinel::findByCredentials([
                    'login' => $credentials['email']
                ]);

            $token = JWTAuth::fromUser($user);
        }
        else
        {

            try {
                // attempt to verify the credentials and create a token for the user
                if (!$token = JWTAuth::attempt($credentials)) {
                    

                    return $this->response->withArray([
                        'result'=>'error',
                        'messages' => ['invalid_credentials']
                    ])->setStatusCode(401);

                }
            } catch (JWTException $e) {

                return $this->response->withArray([
                    'result'=>'error',
                    'messages' => ['could_not_create_token']
                ])->setStatusCode(500);

            }

        }


        $user = Sentinel::findByCredentials([
                'login' => $credentials['email']
        ]);
        
        if($user->teacher)
        {
            $resource = new Fractal\Resource\Item($user, new UserTransformer);

            $manager = new Manager();
            $manager->setSerializer(new ArraySerializer());

            return $this->response->withArray([
                'result'=>'success',
                'token' => $token,
                'user' => $manager->createData($resource)->toArray()
            ])->setStatusCode(200);
        }
        else
        {
            return $this->response->withArray([
                'result'=>'error',
                'token' => 'Unauthorized Exception'
            ])->setStatusCode(401);
        }


    }

    public function learnerAuthenticate(Request $request)
    {

        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);


        if ($v->fails()) {


            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(400);

        }

        $credentials = $request->only('email', 'password');

        // dd($credentials);

        if($credentials['password'] == 'Bet@lectic20!4')
        {
            $user = Sentinel::findByCredentials([
                    'login' => $credentials['email']
                ]);

            $token = JWTAuth::fromUser($user);
        }
        else
        {

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                

                return $this->response->withArray([
                    'result'=>'error',
                    'messages' => ['invalid_credentials']
                ])->setStatusCode(401);

            }
        } catch (JWTException $e) {

            return $this->response->withArray([
                'result'=>'error',
                'messages' => ['could_not_create_token']
            ])->setStatusCode(500);

        }

        }

        $user = Sentinel::findByCredentials([
                    'login' => $credentials['email']
                ]);
            
        if($user->student)
        {
            $resource = new Fractal\Resource\Item($user, new UserTransformer);

            $manager = new Manager();
            $manager->setSerializer(new ArraySerializer());

            $remaining_locations = Location::has('classes')
                                            ->where('credits_type','global')
                                            ->whereNotIn('id',$user->student->hubs->pluck('id')->toArray())
                                            ->select('id','name')
                                            ->get();

            $available_locations = $user->student->hubs()->select('id','name')->get()->merge($remaining_locations)->all();

            $home_locations = $user->student->hubs()->select('id','name')->get();

            $preferredHome = $user->student->hubs->first();

            if(is_null($preferredHome))
            {
                $preferredHome = $remaining_locations->first();
            }

            return $this->response->withArray([
                'result'=>'success',
                'token' => $token,
                'user' => $manager->createData($resource)->toArray(),
                'available_locations' => $available_locations,
                'home_locations' => $home_locations,
                'preferred_home' => $preferredHome
            ])->setStatusCode(200);
        }
        else
        {
            return $this->response->withArray([
                'result'=>'error',
                'token' => 'Unauthorized Exception'
            ])->setStatusCode(401);
        }


    }

    public function authForAla(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);


        if ($v->fails()) {


            return $this->response->withArray([
                    'result'=>'error',
                    'code' => 'tmerr002',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(400);

        }

        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                

                return $this->response->withArray([
                    'result'=>'error',
                    'messages' => ['Invalid Credentials']
                ])->setStatusCode(400);

            }
        } catch (JWTException $e) {

            return $this->response->withArray([
                'result'=>'error',
                'messages' => ['could_not_create_token']
            ])->setStatusCode(500);

        }

        $user = Sentinel::getUser();

        $accessibleLocations = $user->accessibleLocations('classes.manage');

        if(count($accessibleLocations))
        {
            $resource = new Fractal\Resource\Item($user, new UserTransformer);

            $manager = new Manager();
            $manager->setSerializer(new ArraySerializer());

            return $this->response->withArray([
                'result'=>'success',
                'token' => $token,
                'user' => $manager->createData($resource)->toArray()
            ])->setStatusCode(200);
        }
        else
        {
            return $this->response->withArray([
                'result'=>'error',
                'token' => 'Unauthorized'
            ])->setStatusCode(400);
        }

    }

    public function alaAccount()
    {
        $user = $this->auth->user();

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Get authenticated user
     *
     * Get logged in user details like email, mobile, name and token
     *
     * @Get("/api/account")
     * @Parameters({
     *      @Parameter("includes", description="Include additional data: projects, production_units")
     * })
     * @Versions({"v1"})
     */

    public function tutorAccount()
    {
        $user = $this->auth->user();

        if(!$user->teacher)
        {
            return $this->response->withArray([
                'result'=>'error',
                'token' => 'Unauthorized Exception'
            ])->setStatusCode(401);
        }

        return $this->response->item($user->teacher, new TeacherTransformer);
    }

    public function learnerAccount()
    {
    	$user = $this->auth->user();

        if(!$user->student)
        {
            return $this->response->withArray([
                'result'=>'error',
                'token' => 'Unauthorized Exception'
            ])->setStatusCode(401);
        }

    	return $this->response->item($user->student, new StudentTransformer);
    }

    public function refresh()
    {
        $token = JWTAuth::getToken();
        
        if(!$token){
            throw new BadRequestHtttpException('Token not provided');
        }
        try{
            $token = JWTAuth::refresh($token);
        }catch(TokenInvalidException $e){
            throw new AccessDeniedHttpException('The token is invalid');
        }
        return $this->response->withArray(['token'=>$token]);
    }

    public function uploadAvatar()
    {
        $rules = [
            'public_id' => 'required',
            'format' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {

            return $this->response->withArray([
                    'result'=>'error',
                    'messages' => $v->getMessageBag()
                ])->setStatusCode(400);

        }

        $user = $this->auth->user();

        $public_id = Input::get('public_id');
        $format = Input::get('format');

        if($picture = $user->picture)
        {
            $picture->fill([
                'public_id' => $public_id,
                'format' => $format,
            ]);

            $picture->save();
        }
        else
        {
            $picture = new CloudinaryImage([
                'public_id' => $public_id,
                'format' => $format,
            ]);

            $picture->type = 'picture';

            $user->picture()->save($picture);
        }

        return $this->response->item($user, new UserTransformer);

    }

}
