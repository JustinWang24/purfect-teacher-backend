<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 11/11/19
 * Time: 4:15 PM
 */

namespace App\Events\User\Student;
use App\Events\CanReachByMobilePhone;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\User;

abstract class AbstractRegistrationEvent implements CanReachByMobilePhone
{
    /**
     * @var RegistrationInformatics
     */
    public $form;

    protected $user;

    /**
     * ApproveRegistrationEvent constructor.
     * @param RegistrationInformatics $registrationForm
     */
    public function __construct(RegistrationInformatics $registrationForm)
    {
        $this->form = $registrationForm;
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
}