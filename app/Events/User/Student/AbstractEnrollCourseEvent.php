<?php


namespace App\Events\User\Student;


use App\Dao\Users\UserDao;
use App\Events\HasEnrollCourseForm;
use App\User;

abstract class AbstractEnrollCourseEvent implements HasEnrollCourseForm
{
    public $form;

    protected $user;

    /**
     *
     * @param array $enrollCourseForm
     */
    public function __construct(array $enrollCourseForm)
    {
        $this->form = $enrollCourseForm;
    }

    public function getUser(): User
    {
        if(!$this->user){
            $userDao = new UserDao();
            $this->user = $userDao->getUserById($this->form['user_id']);
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
