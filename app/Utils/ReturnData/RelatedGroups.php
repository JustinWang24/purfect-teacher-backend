<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Utils\ReturnData;


use App\Dao\Schools\GradeDao;
use App\Dao\Schools\MajorDao;
use App\Dao\Schools\OrganizationDao;
use App\User;
use App\Utils\Time\GradeAndYearUtil;

class RelatedGroups
{
    /**
     * @var User $user
     */
    private $user;
    private $schoolId;
    private $level;

    public function __construct($user, $schoolId, $level = 1)
    {
        $this->user = $user;
        $this->schoolId = $schoolId;
        $this->level = $level;
    }

    /**
     * @return array
     */
    public function toArray(){
        $result = [];
        if($this->user->isSchoolManager()){
            // 那就返回所有的: 部门加 年级/班级
            $result = $this->forSchoolManager();
        }
        elseif($this->user->isEmployee() || $this->user->isTeacher()){
            // 返回权限之内的部门 加 年级班级
        }
        elseif($this->user->isStudent()){
            // 如果是学生
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function forSchoolManager(){
        // 那就返回所有的: 部门加 年级/班级
        $orgDao = new OrganizationDao();
        $organizations = $orgDao->loadByLevel($this->level,$this->schoolId);
        $data['org'] = [
            'level'=>$this->level,
            'organizations'=>$organizations
        ];

        // 全部年级和班级
        $data['years'] = [];
        $years = GradeAndYearUtil::GetAllYears();
        $gradeDao = new GradeDao();
        foreach ($years as $year) {
            $data['years'][] = [
                'year'=>$year,
                'grades'=>$gradeDao->getBySchoolAndYearForApp($this->schoolId, $year, true)
            ];
        }

        // 全部专业
        $majorDao = new MajorDao();
        $data['majors'] = $majorDao->getMajorsBySchool($this->schoolId);

        return $data;
    }
}