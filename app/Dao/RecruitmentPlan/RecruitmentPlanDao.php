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
//        $planData['start_at'] = GradeAndYearUtil::ConvertJsTimeToCarbon($planData['start_at']);
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
            ->skip($pageNumber * $pageSize)
            ->take($pageSize)
            ->get();
    }


    /**
     *根据ID获取专业详情包括专业下的课程
     * @param $majorId
     * @return mixed
     */
    public function getMajorDetailById($majorId)
    {
        $data = RecruitmentPlan::where('id', $majorId)->select('id', 'major_id', 'seats', 'enrolled_count', 'applied_count')->with([
                    'courseMajor' => function ($query) {
                        $query->select('major_id', 'course_name');
                    },
                    'major' => function ($query) {
                        $query->select('id', 'name', 'fee', 'period', 'description');
                    }
                ])->first();

        return $data;
    }


    /**
     * 根据学校id 获取招生的专业
     * @param $schoolId
     * @return array
     */
    public function getAllMajorBySchoolId($schoolId)
    {
        $data = RecruitmentPlan::where('school_id', $schoolId)
                    ->select('id', 'major_id', 'seats', 'enrolled_count', 'applied_count', 'hot')->with([
                    'major' => function ($query) {
                        $query->select('id', 'name', 'fee', 'period');
                    }
                ])->orderBy('created_at', 'desc')->get();

        $result = [];
        foreach ($data as $key => $val) {
            $result[$key]['id']       = $val['id'];
            $result[$key]['major_id'] = $val['major_id'];
            $result[$key]['name']     = $val['major']['name'];
            $result[$key]['fee']      = $val['major']['fee'];
            $result[$key]['period']   = $val['major']['period'];
            $result[$key]['seats']    = $val['seats'];
            $result[$key]['enrolled'] = $val['enrolled_count'];
            $result[$key]['applied']  = $val['applied_count'];
        }

        return  $result;
    }
}
