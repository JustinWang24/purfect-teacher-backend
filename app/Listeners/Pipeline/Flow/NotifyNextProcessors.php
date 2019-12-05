<?php

namespace App\Listeners\Pipeline\Flow;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\IFlowAccessor;

class NotifyNextProcessors
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
        //
    }
}
