<?php

namespace Aham\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Aham\Models\SQL\AhamClass;

class LogMyName
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $class;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AhamClass $class)
    {
        $this->class = $class;
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
