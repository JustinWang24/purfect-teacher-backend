<?php

namespace App\Listeners\User\Student;

use App\Events\User\Student\ApproveRegistrationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CanReachByMobilePhone;

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
     * @param  CanReachByMobilePhone  $event
     * @return void
     */
    public function handle(CanReachByMobilePhone $event)
    {
        //
    }
}
