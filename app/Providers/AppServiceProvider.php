<?php

namespace Aham\Providers;

use Illuminate\Support\ServiceProvider;
use Aham\Traits\UniquifyInterface;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use Sentinel;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $manager = new Manager();
            $manager->setSerializer(new ArraySerializer());

            return new \Dingo\Api\Transformer\Adapter\Fractal($manager, 'include', ',');
        });

        app('Dingo\Api\Exception\Handler')->register(function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $exception) {
            return \Response::make([
                'result' => 'error',
                'message' => 'Unauthorized Exception'
            ], 401);
        });

        \Laravel\Horizon\Horizon::auth(function (Request $request) {
            if (Sentinel::check()) {
                $user = Sentinel::getUser();
                if ($user->hasAccess('admin')) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        \Cloudinary::config([
          'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
          'api_key' => env('CLOUDINARY_API_KEY'),
          'api_secret' => env('CLOUDINARY_API_SECRET')
        ]);

        $this->app->bind(
            'Aham\Services\Storage\CDNInterface',
            'Aham\Services\Storage\AWSCDN'
        );

        $this->app->bind(
            'Aham\Services\SMS\SMSInterface',
            'Aham\Services\SMS\SMSMVaaYoo'
        );

        $this->app->bind(
            'Aham\TDGateways\UserGatewayInterface',
            'Aham\TDGateways\Implementations\UserGateway'
        );

        $this->app->bind(
            'Aham\TDGateways\TopicGatewayInterface',
            'Aham\TDGateways\Implementations\TopicGateway'
        );

        $this->app->bind(
            'Aham\TDGateways\LocationManagementGatewayInterface',
            'Aham\TDGateways\Implementations\LocationManagementGateway'
        );

        $this->app['events']->listen('eloquent.creating*', function ($model) {
            if ($model instanceof UniquifyInterface) {
                $model->uniquify();
            }
        });

        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
    }
}
