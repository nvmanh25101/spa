<?php

namespace App\Listeners;

use App\Enums\NotiType;
use App\Events\NewOrderReceived;
use App\Mail\OrderMail;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class StoreNotification
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
    public function handle(NewOrderReceived $event): void
    {
        Notification::query()->create([
            'message' => $event->message,
            'object_id' => $event->order->id,
            'type' => NotiType::DON_HANG,
        ]);
        $user = Customer::query()->findOrFail($event->order->customer_id);
        Mail::to($user->email)->send(new OrderMail($event->order, $user->name));
    }
}
