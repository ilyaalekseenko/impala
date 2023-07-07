<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateMainLogistEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $logistId;
    public $unreadHeaderArr;
    public $mainHeaderArr;

    public function __construct($logistId,$unreadHeaderArr,$mainHeaderArr)
    {
        $this->logistId = $logistId;
        $this->unreadHeaderArr = $unreadHeaderArr;
        $this->mainHeaderArr = $mainHeaderArr;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('logist');
    }
}
