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

    /**
     * 是否有列出所有状态的报名表的标志位
     * @return bool
     */
    public function needAllStatus(){
        return $this->has('status') && $this->get('status') === 'all';
    }

    /**
     * 是否有只列出 等待的报名表 的标志位
     * @return bool
     */
    public function isInWaitingStatus(){
        return $this->has('status') && $this->get('status') === 'waiting';
    }

    /**
     * 是否有只列出 等待的报名表 的标志位
     * @return bool
     */
    public function isInRefusedStatus(){
        return $this->has('status') && $this->get('status') === 'refused';
    }

    /**
     * 是否有只列出批准的报名表的标志位
     * @return bool
     */
    public function isInPassedStatus(){
        return $this->has('status') && $this->get('status') === 'pass';
    }

    /**
     * 是否有只列出已录取的新生的标志位
     * @return bool
     */
    public function isInApprovedStatus(){
        return $this->has('status') && $this->get('status') === 'approved';
    }

    public function getStatus(){
        return $this->get('status');
    }
}