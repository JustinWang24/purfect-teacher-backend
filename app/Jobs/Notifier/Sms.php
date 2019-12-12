<?php

namespace App\Jobs\Notifier;

use App\Models\Contract\ContentHolder;
use App\Models\Contract\HasMobilePhone;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Utils\Misc\SmsFactory;

class Sms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receivers;
    protected $contentHolder;
    protected $template;

    /**
     * Sms constructor.
     * @param $receivers
     * @param $contentHolder
     * @param $template
     */
    public function __construct($receivers, $contentHolder, $template)
    {
        $this->receivers = $receivers;
        $this->contentHolder = $contentHolder;
        $this->template = $template;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms = SmsFactory::GetInstance();

        if(count($this->receivers) === 1){
            $result = $sms->send($this->receivers, $this->template, $this->contentHolder);
            Log::warning('队列发送短信了');
        }
        elseif(count($this->receivers) > 1){
            // Todo 群发短信
        }
    }
}
