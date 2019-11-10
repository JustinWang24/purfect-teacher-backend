<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/11/19
 * Time: 2:20 PM
 */

namespace App\Http\Requests\Teacher;


use App\Http\Requests\MyStandardRequest;

class RecruitmentRegistrationFormRequest extends MyStandardRequest
{
    public function getPlanId(){
        return $this->get('plan');
    }

    public function getUserUuid(){
        return $this->get('user');
    }
}