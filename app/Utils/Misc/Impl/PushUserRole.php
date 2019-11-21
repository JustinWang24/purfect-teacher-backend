<?php


namespace App\Utils\Misc\Impl;

use App\Models\Acl\Role;
use App\User;

trait PushUserRole
{
    protected function setKeyAndRegId($users)
    {
        $student = [];
        $teacher = [];
        $shop    = [];
        /**
         * @var  user $user
         */
        foreach ($users as $user) {

            if ($user->isStudent()) {
                // 学生端
                $this->appKey       = env('PUSH_STUDENT_KEY');
                $this->masterSecret = env('PUSH_STUDENT_SECRET');

                $student['key'] = ['appKey' => $this->appKey , 'masterSecret' => $this->masterSecret];
                foreach ($user->userDevices as $device) {
                    $student['regId'][] = $device->push_id;
                }

            } elseif ($user->isTeacher() || $user->isEmployee()) {
                // 教师端
                $this->appKey       = env('PUSH_TEACHER_KEY');
                $this->masterSecret = env('PUSH_TEACHER_SECRET');

                $teacher['key'] = ['appKey' => $this->appKey , 'masterSecret' => $this->masterSecret];

                foreach ($user->userDevices as $device) {
                    $teacher['regId'][] = $device->push_id;
                }

            } elseif ($user->type == Role::COMPANY || $user->type == Role::DELIVERY || $user->type == Role::BUSINESS_INNER || $users == BUSINESS_OUTER) {
                // TODO :: 以后实现

                // 商企端
                $this->appKey       = env('PUSH_ENTERPRISE_KEY');
                $this->masterSecret = env('PUSH_ENTERPRISE_SECRET');
                $shop['key'] = ['appKey' => $this->appKey , 'masterSecret' => $this->masterSecret];
                foreach ($user->userDevices as $device) {
                    $shop['regId'][] = $device->push_id;
                }

            } else {
                $this->appKey       = null;
                $this->masterSecret = null;
            }
        }
        return [$student,  $teacher, $shop];
    }

}
