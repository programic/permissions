<?php

namespace Programic\Permission\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RoleAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roleUser;

    /**
     * Create a new event instance.
     *
     * @param $roleUser
     */
    public function __construct($roleUser)
    {
        $this->roleUser = $roleUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('role-assigned');
    }
}
