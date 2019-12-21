<?php

namespace App\Models\Teachers;
use App\Models\Schools\TeachingAndResearchGroup;
use App\Models\Schools\TeachingAndResearchGroupMember;
use App\Models\Teachers\Performance\TeacherPerformance;
use App\Models\Users\UserOrganization;
use App\User;
use App\Utils\Pipeline\IFlow;

class Teacher extends User
{
    protected $table = 'users';

    /**
     * 老师需要使用的流程的类型集合
     * @return array
     */
    public static function FlowTypes(){
        return [IFlow::TYPE_OFFICE];
    }

    public function performances(){
        return $this->hasMany(TeacherPerformance::class, 'user_id')->orderBy('year','desc');
    }

    public function userOrganization(){
        return $this->hasOne(UserOrganization::class);
    }

    /**
     * 获取老师所在的教研组集合
     *
     * 处理的情况包括: 老师在某个教研组人组长, 则 isLeader 为真, 否则为假
     *
     * @return TeachingAndResearchGroup[]
     */
    public function myTeachingAndResearchGroup(){
        $data = [];
        $groupMembers = TeachingAndResearchGroupMember::where('user_id',$this->id)->with('group')->get();
        if($groupMembers){
            // 表示为教研组的成员, 不是教研组的组长
            foreach ($groupMembers as $groupMember) {
                $groupMember->group->isLeader = false;
                $data[] = $groupMember->group;
            }
        }
        else{
            $groups = TeachingAndResearchGroup::where('user_id',$this->id)->get();
            foreach ($groups as $group){
                $group->isLeader = true;
                $data[] = $group;
            }
        }
        return $data;
    }
}
