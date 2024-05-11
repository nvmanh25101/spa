<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAppointmentReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;
    public $message = 'Có lịch đặt spa mới';

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('appointments');
    }

    public function broadcastAs(): string
    {
        return 'new-appointment';
    }
}
