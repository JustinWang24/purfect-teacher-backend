<?php


namespace App\Events\User\Student;


use App\Events\HasEnrollCourseForm;
use App\Models\ElectiveCourses\StudentEnrolledOptionalCourse;
use App\User;

abstract class AbstractEnrollCourseEvent implements HasEnrollCourseForm
{
    public $form;

    protected $user;

    /**
     * ApproveRegistrationEvent constructor.
     * @param StudentEnrolledOptionalCourse $enrollCourseForm
     */
    public function __construct(StudentEnrolledOptionalCourse $enrollCourseForm)
    {
        $this->form = $enrollCourseForm;
    }

    public function getUser(): User
    {
        if(!$this->user){
            $this->user = $this->form->user;
        }
        return $this->user;
    }

    public function getMobileNumber(): string
    {
        return $this->getUser()->mobile;
    }

    public function getUserName(): string
    {
        return $this->getUser()->name;
    }
}
