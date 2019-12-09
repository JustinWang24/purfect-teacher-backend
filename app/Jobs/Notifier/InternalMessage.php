<?php
/**
 * 这个是系统内部发送消息的类
 */
namespace App\Jobs\Notifier;

use App\Dao\Misc\SystemNotificationDao;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class InternalMessage
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $schoolId;
    protected $from;
    protected $to;
    protected $type;
    protected $priority;
    protected $content;
    protected $nextMove;

    /**
     * InternalMessage constructor.
     * @param $schoolId
     * @param $from
     * @param $to
     * @param $type
     * @param $priority
     * @param $content
     * @param null $nextMove
     */
    public function __construct(
        $schoolId,
        $from,
        $to,
        $type,
        $priority,
        $content,
        $nextMove = null
    ){
        $this->schoolId = $schoolId;
        $this->from = $from;
        $this->to = $to;
        $this->type = $type;
        $this->priority = $priority;
        $this->content = $content;
        $this->nextMove = $nextMove;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $dao = new SystemNotificationDao();
            $dao->create([
                'sender'=>$this->from,
                'to'=>$this->to,
                'type'=>$this->type,
                'priority'=>$this->priority,
                'school_id'=>$this->schoolId,
                'content'=>$this->content,
                'next_move'=>$this->nextMove,
            ]);
        }catch (\Exception $exception){
            Log::alert('创建系统消息失败',['msg'=>$exception->getMessage()]);
        }
    }
}
