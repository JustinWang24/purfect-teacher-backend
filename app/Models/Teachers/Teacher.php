<?php

namespace App\Models\Teachers;
use App\Models\Schools\GradeManager;
use App\Models\Schools\TeachingAndResearchGroup;
use App\Models\Schools\TeachingAndResearchGroupMember;
use App\Models\Schools\YearManager;
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

    public static function myUserOrganization($userId){
        return UserOrganization::where('user_id', $userId)->first();
    }

    public static function myGradeManger($userId)
    {
        return GradeManager::where('adviser_id', $userId)->first();
    }

    public static function myYearManger($userId)
    {
        return YearManager::where('user_id', $userId)->first();
    }


    /**
     * 获取老师所在的教研组集合
     *
     * 处理的情况包括: 老师在某个教研组人组长, 则 isLeader 为真, 否则为假
     *
     * @return TeachingAndResearchGroup[]
     */
    public static function myTeachingAndResearchGroup($userId){
        $data = [];
        $groupMembers = TeachingAndResearchGroupMember::where('user_id',$userId)->with('group')->get();
        if($groupMembers){
            // 表示为教研组的成员, 不是教研组的组长
            foreach ($groupMembers as $groupMember) {
                $groupMember->group->isLeader = false;
                $data[] = $groupMember->group;
            }
        }
        else{
            $groups = TeachingAndResearchGroup::where('user_id',$userId)->get();
            foreach ($groups as $group){
                $group->isLeader = true;
                $data[] = $group;
            }
        }
        return $data;
    }

    /**
     * 获取老师所有职务
     * @param $userId
     */
    public static function getTeacherAllDuties($userId)
    {
        
    }



}
