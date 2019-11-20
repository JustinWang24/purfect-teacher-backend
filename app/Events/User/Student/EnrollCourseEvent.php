<?php


namespace App\Events\User\Student;


use App\Models\ElectiveCourses\StudentEnrolledOptionalCourse;
use App\Models\Misc\SystemNotification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnrollCourseEvent extends AbstractEnrollCourseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(StudentEnrolledOptionalCourse $form)
    {
        parent::__construct($form);
    }

    /**
     * 获取form
     * @return StudentEnrolledOptionalCourse
     */
    public function getForm(): StudentEnrolledOptionalCourse
    {
        return $this->form;
    }

    /**
     * 获取消息类别
     * @return int
     */
    public function getMessageType(): int
    {
        return SystemNotification::PRIORITY_MEDIUM;
    }

    /**
     * 获取消息级别
     * @return int
     */
    public function getPriority(): int
    {
        return SystemNotification::TYPE_STUDENT_REGISTRATION;
    }

    /**
     * 获取内容
     * @return string
     */
    public function getSystemContent(): string
    {
        $course = $this->form->course()->first();
        return 'Hi '.$this->getUser()['name'].'您的选修课《'.$course->name.'》已经报名成功';
    }

    /**
     * 获取下一步
     * @return string
     */
    public function getNextMove(): string
    {
        // TODO: Implement getNextMove() method.
        return '';
    }
}
