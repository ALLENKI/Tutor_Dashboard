<?php

namespace Aham\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Aham\Models\SQL\User;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $userGateway;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->userGateway = app()->make('Aham\TDGateways\UserGatewayInterface');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->userGateway->sendWelcomeMail($this->user);
    }
}
