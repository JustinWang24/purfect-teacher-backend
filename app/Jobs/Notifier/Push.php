<?php

namespace App\Jobs\Notifier;

use App\User;
use App\Utils\Misc\Contracts\INextAction;
use App\Utils\Misc\JPushFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;

class Push implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $title;

    private $content;

    private $nextStep;

    private $noticedTo;

    /**
     * Push constructor.
     * @param User[] $noticedTo
     * @param string $title
     * @param string $content
     * @param INextAction $nextStep
     */
    public function __construct($noticedTo, $title, $content, $nextStep = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->nextStep = $nextStep;
        $this->noticedTo = $noticedTo;
    }

    public function handle()
    {

        if(env('APP_DEBUG', false)){
            Log::info('流程开始111',['msg'=> '123123123']);
        }
        else{
            $push = JPushFactory::GetInstance();
            foreach ($this->noticedTo as $user) {
                $url = null;
                if($this->nextStep)
                    $url = $this->nextStep->getActionUrl($user);

                $response = $push->send();
            }
        }
    }


}
