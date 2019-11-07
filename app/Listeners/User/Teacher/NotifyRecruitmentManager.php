<?php

namespace App\Listeners\User\Teacher;

use App\Events\User\Student\ApplyRecruitmentPlanEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyRecruitmentManager
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
     * @param  ApplyRecruitmentPlanEvent  $event
     * @return void
     */
    public function handle(ApplyRecruitmentPlanEvent $event)
    {

    }
}
