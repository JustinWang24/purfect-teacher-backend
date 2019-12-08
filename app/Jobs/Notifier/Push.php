<?php

namespace App\Jobs\Notifier;

use App\User;
use App\Utils\Misc\Contracts\INextAction;
use App\Utils\Misc\JPushFactory;
use Illuminate\Contracts\Queue\ShouldQueue;

class Push implements ShouldQueue
{
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
    public function __construct( $noticedTo, $title, $content, $nextStep = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->nextStep = $nextStep;
        $this->noticedTo = $noticedTo;
    }

    public function handle()
    {
        Log::info('流程开始111',['msg'=> '123123123']);
//        $push = JPushFactory::GetInstance();
//        foreach ($this->noticedTo as $user) {
//            $url = null;
//            if($this->nextStep)
//                $url = $this->nextStep->getActionUrl($user);
//
//            $response = $push->send();
//        }
    }


}
