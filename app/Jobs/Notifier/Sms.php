<?php

namespace App\Jobs\Notifier;

use App\Models\Contract\ContentHolder;
use App\Models\Contract\HasMobilePhone;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Sms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $receivers;
    private $contentHolder;
    private $template;

    /**
     * Sms constructor.
     * @param HasMobilePhone[] $receivers
     * @param ContentHolder $contentHolder: 应该是需要插入到模板中的内容
     * @param string|int $template: 应该是需要发送的模板的 ID
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
        if(count($this->receivers) === 1){
            // 点对点的短信发送
        }
        elseif(count($this->receivers) > 1){
            // 群发短信
        }
    }
}
