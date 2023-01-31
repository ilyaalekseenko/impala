<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateLogistEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $counter;
    public $logist_number;
    public $delete_counter;
    public $delete_logist_number;
    public function __construct($counter,$logist_number,$delete_counter,$delete_logist_number)
    {
        $this->counter = $counter;
        $this->logist_number = $logist_number;
        $this->delete_counter = $delete_counter;
        $this->delete_logist_number = $delete_logist_number;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('logist');
    }
}
