<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Business\Impl;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StudentLeaveLogic
{
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle($options)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        try {
            $dateArr = explode('~', $options['date_date']);
            $start = Carbon::parse(trim($dateArr[0]));
            $end   = Carbon::parse(trim($dateArr[1]));
            //@TODO 学生请假相关业务
            /*$this->user 请假的学生 User
            $start 请假开始时间
            $end 请假结束时间*/
            Log::info('收到学生请假的业务通知', [$start, $end, $this->user]);

            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }catch (\Exception $exception) {
            $bag->setMessage( $exception->getMessage());
        }
        return $bag;
    }

}
