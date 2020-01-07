<?php


namespace App\Events\User;


use App\Dao\OA\OaAttendanceTeacherDao;
use App\Events\CanReachByMobilePhone;
use App\Models\Timetable\TimetableItem;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeacherBeLateEvent implements CanReachByMobilePhone
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $timeTabItem;

    function __construct(TimetableItem $timetableItem)
    {
        $this->timeTabItem = $timetableItem;
    }

    /**
     * 必须可以拿到电话号码
     * @return string
     */
    public function getMobileNumber(): string
    {
        $dao = new OaAttendanceTeacherDao();
        $members = $dao->getUserListForReceiveMessage($this->timeTabItem->school_id);
        foreach ($members as $member)
        {
            return strval($member->user->mobile);
        }
    }

    /**
     * 必须可以拿到短信通知的模板 ID
     * @return string
     */
    public function getSmsTemplateId(): string
    {
        // TODO: Implement getSmsTemplateId() method.
        // 此处的模板id要根据最终发短信内容来设置，此处临时实现
        return  483489;
    }

    /**
     * 必须可以拿到需要发送的短信的动态内容数组
     * @return array
     */
    public function getSmsContent(): array
    {
        return [$this->timeTabItem->room->building->name,
                $this->timeTabItem->room->name,
                $this->timeTabItem->teacher->name,
                $this->timeTabItem->grade->name,
        ];
    }

    /**
     * 必须可以拿到用户的对象
     * @return User
     */
    public function getUser(): User
    {
        return $this->timeTabItem->teacher;
    }
}
