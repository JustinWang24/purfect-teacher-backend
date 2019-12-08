<?php

namespace App\Listeners\Pipeline\Flow;

use App\BusinessLogic\Pipeline\CreateActionMessage\Factory;
use App\Events\IFlowAccessor;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifyStarter implements ShouldQueue
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
     * @param  IFlowAccessor  $event
     * @return void
     */
    public function handle(IFlowAccessor $event)
    {
        $logic = Factory::GetInstance($event->getNode(), $event->getUser());
        $logic->actionAndMessage($event->getAction());
    }
}
