<?php

namespace App\Listeners;

use App\Jobs\SendEmail;
use App\Mail\OrderDetails;

class SendOrderDetails
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
    public function handle(object $event): void
    {
        SendEmail::dispatch($event->order->user, new OrderDetails($event->order));
    }
}
