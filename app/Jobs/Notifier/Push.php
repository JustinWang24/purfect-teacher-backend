<?php

namespace App\Jobs\Notifier;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Utils\Misc\JPushFactory;

class Push implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;

    protected $content;

    protected $nextStep;

    protected $noticedTo;

    /**
     * Push constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->nextStep = $data['nextStep'];
        $this->noticedTo = $data['noticedTo'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(env('APP_DEBUG', false)){
            Log::info('推消息',['msg'=> $this->title,'content'=>$this->content]);
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
