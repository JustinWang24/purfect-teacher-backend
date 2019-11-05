<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/11/19
 * Time: 5:08 PM
 */

namespace App\Dao\RecruitmentPlan;
use App\Models\Schools\RecruitmentPlan;
use App\Utils\Time\GradeAndYearUtil;
use Illuminate\Support\Facades\DB;

class RecruitmentPlanDao
{
    private $schoolId;
    public function __construct($schoolId){
        $this->schoolId = $schoolId;
    }

    public function getPlan($id){
        return RecruitmentPlan::find($id);
    }

    /**
     * @param $planData
     * @return RecruitmentPlan
     */
    public function createPlan($planData){
        if(empty($planData['tags'])){
            $planData['tags'] = $planData['major_name'];
        }
        return RecruitmentPlan::create($planData);
    }

    /**
     * 更新计划
     * @param $planData
     * @return bool
     */
    public function updatePlan($planData){
        $id = $planData['id']??null;
        unset($planData['id']);
        if($id){
            return RecruitmentPlan::where('id',$id)->update($planData);
        }
        return false;
    }

    public function deletePlan($id, $hardDelete = false){
        if($hardDelete){
            return DB::table('recruitment_plans')->delete($id);
        }
        else{
            return RecruitmentPlan::where('id',$id)->delete();
        }
    }

    /**
     * 获取某个学校的招生简章
     *
     * @param $schoolId
     * @param null $year
     * @param int $pageNumber
     * @param int $pageSize
     * @return RecruitmentPlan[]
     */
    public function getPlansBySchool($schoolId, $year = null, $pageNumber = 0, $pageSize = 20){
        if(!$year){
            $year = date('Y');
        }
        return RecruitmentPlan::where('school_id', $schoolId)
            ->where('year',$year)
            ->orderBy('updated_at','desc')
            ->skip($pageNumber * $pageSize)
            ->take($pageSize)
            ->get();
    }
}