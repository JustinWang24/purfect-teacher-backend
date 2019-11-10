<?php

namespace App\Listeners\User\Student;

use App\Events\User\Student\ApproveRegistrationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyStudent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ApproveRegistrationEvent  $event
     * @return void
     */
    public function handle(ApproveRegistrationEvent $event)
    {
        //
    }
}
