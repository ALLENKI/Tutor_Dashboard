<?php

namespace Aham\TDGateways\Implementations;

use Aham\TDGateways\UserGatewayInterface;
use Aham\Models\SQL\User;
use Aham\Jobs\SendActivationEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Aham\Managers\SuperMail;

use Sentinel;
use Activation;
use Mail;

class UserGateway extends AbstractGateway implements UserGatewayInterface
{
    use DispatchesJobs;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function createUser($data)
    {
        $user = Sentinel::register($data);

        $this->dispatch(new SendActivationEmail($user));
    }

    public function sendActivationMail($user)
    {
        $activation = Activation::exists($user);

        if (!$activation) {
            $activation = Activation::create($user);
        }

        $link = route('auth::activate', [$user->id, $activation->code]);

        SuperMail::mail('emails_new.auth.activate',['email' => $user->email, 'link' => $link, 'name' => $user->name],'Please Activate Your Email',[$user->email => $user->name]);

        return true;
    }

    public function activate()
    {

    }

    public function sendWelcomeMail($user)
    {

        Mail::send('emails.welcome', ['email' => $user->email], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Welcome to Aham');
        });

        return true;
    }

    public function updateUser(User $user, $data)
    {
        $user->fill($data);

        $user->save();

        return true;
    }
}
