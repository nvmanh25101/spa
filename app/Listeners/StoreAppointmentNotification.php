<?php

namespace App\Listeners;

use App\Enums\NotiType;
use App\Events\NewAppointmentReceived;
use App\Mail\AppointmentMail;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class StoreAppointmentNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewAppointmentReceived $event): void
    {
        Notification::query()->create([
            'message' => $event->message,
            'object_id' => $event->appointment->id,
            'type' => NotiType::LICH,
        ]);

        Mail::to($event->appointment->email_booker)->send(new AppointmentMail($event->appointment));
    }
}
