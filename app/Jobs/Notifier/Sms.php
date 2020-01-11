<?php

namespace App\Jobs\Notifier;

use App\Dao\Users\UserVerificationDao;
use App\Models\Contract\ContentHolder;
use App\Models\Contract\HasMobilePhone;
use App\Models\Users\UserVerification;
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
    protected $user;
    protected $action;

    /**
     * Sms constructor.
     * @param $receivers
     * @param $contentHolder
     * @param $template
     * @param $user
     * @param $action
     */
    public function __construct($receivers, $contentHolder, $template, $user, $action)
    {
        $this->receivers = $receivers;
        $this->contentHolder = $contentHolder;
        $this->template = $template;
        $this->user = $user;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $sms = SmsFactory::GetInstance();
         $result = $sms->send($this->receivers, $this->template, $this->contentHolder);
         if ($result->isSuccess()) {
             $dao = new UserVerificationDao;
             $data = [
                 'user_id' => $this->user->id,
                 'purpose' => $this->action,
                 'mobile'  => $this->receivers,
                 'code'  => $this->contentHolder[0],
             ];
             $dao->create($data);
         }

         Log::channel('smslog')->alert('队列发送短信了');
    }
}
