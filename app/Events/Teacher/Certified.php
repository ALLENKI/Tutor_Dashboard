<?php

namespace Aham\Events\Teacher;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Aham\Models\SQL\TeacherCertification;

class Certified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $certification;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TeacherCertification $certification)
    {
        $this->certification = $certification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
