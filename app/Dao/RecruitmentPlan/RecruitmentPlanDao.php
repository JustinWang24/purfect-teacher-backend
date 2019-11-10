<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/11/19
 * Time: 5:08 PM
 */

namespace App\Dao\RecruitmentPlan;
use App\Dao\Users\UserDao;
use App\Models\Schools\RecruitmentPlan;
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
     * @param string $userUuid
     * @param null $year
     * @param int $pageNumber
     * @param int $pageSize
     * @return RecruitmentPlan[]
     */
    public function getPlansBySchool($schoolId, $userUuid = null, $year = null, $pageNumber = 0, $pageSize = 20){
        $query =  RecruitmentPlan::where('school_id', $schoolId)
            ->orderBy('updated_at','desc')
            ->skip($pageNumber * $pageSize)
            ->take($pageSize);

        if($userUuid){
            $userDao = new UserDao();
            $u = $userDao->getUserByUuid($userUuid);
            if($u){
                if($u->isTeacher()){
                    // 如果不是超级管理员或者学校的管理员, 那么只加载该老师负责的
                    $query->where('manager_id',$u->id);
                }
            }
        }

        if($year){
            $query->where('year',$year);
        }
        return $query->get();
    }

    /**
     * 加载对于今天依然有效的招生简章
     *
     * @param $today
     * @param $schoolId
     * @return RecruitmentPlan[]
     */
    public function getPlansBySchoolForToday($today, $schoolId){
        return  RecruitmentPlan::where('school_id', $schoolId)
            ->where('start_at','<=',$today->format('Y-m-d'))
            ->where(function ($query) use($today){
                $query->whereNull('end_at')->orWhere('end_at','>=',$today->format('Y-m-d'));
            })
            ->orderBy('id','asc')
            ->get();
    }


    /**
     *根据ID获取专业详情包括专业下的课程
     * @param $majorId
     * @return mixed
     */
    public function getMajorDetailById($majorId)
    {
        $data = RecruitmentPlan::where('major_id', $majorId)->select('id', 'major_id', 'seats', 'enrolled_count', 'applied_count')->with([
                    'courseMajor' => function ($query) {
                        $query->select('major_id', 'course_name');
                    },
                    'major' => function ($query) {
                        $query->select('id', 'name', 'fee', 'period', 'description');
                    }
                ])->first();

        $data = $data->toArray();
        $result = [];
        if (is_array($data) && !empty($data)) {
            $result['major']['id']          = is_null($data['major_id']) ? '' : $data['major_id'];
            $result['major']['seats']       = is_null($data['seats']) ? '' : $data['seats'];
            $result['major']['enrolled']    = is_null($data['enrolled_count']) ? '' : $data['enrolled_count'];
            $result['major']['applied']     = is_null($data['applied_count']) ? '' : $data['applied_count'];
            $result['major']['name']        = is_null($data['major']['name']) ? '' : $data['major']['name'];
            $result['major']['fee']         = is_null($data['major']['fee']) ? '' : $data['major']['fee'];
            $result['major']['period']      = is_null($data['major']['period']) ? '' : $data['major']['period'];
            $result['major']['description'] = is_null($data['major']['description']) ? '' : $data['major']['description'];

            foreach ($data['course_major'] as $key => $val) {
                $result['courses'][] = $val;
                unset($result['courses'][$key]['major_id']);
            }
        }

        return $result;
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


    /**
     * 根据ID 获取一条招生计划
     * @param $id
     * @return mixed
     */
    public function getRecruitmentPlanById($id)
    {
        return RecruitmentPlan::find($id);
    }


    /**
     * 获取招生计划
     * @param $map
     * @param $field
     * @return mixed
     */
    protected function getRecruitmentPlan($map, $field) {
        return RecruitmentPlan::where($map)->select($field)->get();
    }


    /**
     * 根据专业ID和年份获取招生计划
     * @param int $majorId 专业ID
     * @param int $year 年
     * @param int $type 招生类型
     * @return mixed
     */
    public function getRecruitmentPlanByMajorAndYear($majorId, $year, $type) {
        $field = ['id', 'school_id', 'major_id', 'year', 'seats','type'];
        $map = ['major_id'=>$majorId, 'year'=>$year,'type'=>$type];
        return $this->getRecruitmentPlan($map, $field);
    }

    /**
     * 对指定的招生计划的已报名人数字段执行自增操作
     * @param $planId
     * @return bool|int
     */
    public function increaseAppliedCountNumber($planId){
        $plan = DB::table('recruitment_plans')
            ->select(['id','applied_count'])
            ->where('id',$planId)
            ->first();


        if($plan){
            $count = $plan->applied_count + 1;
            DB::table('recruitment_plans')
                ->where('id',$planId)
                ->update(['applied_count'=>$count]);
            return $count;
        }
        return false;
    }
}
