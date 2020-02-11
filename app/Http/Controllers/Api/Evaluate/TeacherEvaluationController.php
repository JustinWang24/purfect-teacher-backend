<?php

namespace App\Http\Controllers\Api\Evaluate;

use App\Dao\Evaluate\EvaluateDao;
use App\Dao\Evaluate\EvaluateStudentRecordDao;
use App\Dao\Evaluate\EvaluateStudentTitleDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Users\GradeUserDao;
use App\Http\Controllers\Controller;
use App\Models\Acl\Role;
use App\Models\Evaluate\Evaluate;
use App\Models\Teachers\TeacherQualification;
use App\User;
use App\Utils\JsonBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherEvaluationController extends Controller
{

    /**
     * 是否开启评学
     * @param Request $request
     * @return string
     */
    public function isEvaluation(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new EvaluateStudentTitleDao;
        $title = $dao->getEvaluateTitleBySchoolId($schoolId);
        if ($title) {
            return JsonBuilder::Success(['evaluate_title_id' => $title->id]);
        } else {
            return JsonBuilder::Error('管理员还没有开启评学');
        }
    }

    /**
     * 教师评价班级列表
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $dao  = new TimetableItemDao;

        $grade = $dao->getTeacherTeachingGrade($user->id);

        $gradeIds = [];
        foreach ($grade as $key => $val) {
            $gradeIds[] = $val->grade_id;
        }

        $gradeIds = array_unique($gradeIds);

        $gradeDao = new GradeDao;
        $grades   = $gradeDao->getGrades($gradeIds);

        $data = [];
        foreach ($grades as $k => $v) {
            $data[$k]['grade_id'] = $v->id;
            $data[$k]['name']     = $v->name;
        }

        return JsonBuilder::Success($data);
    }

    /**
     * 班级所有学生
     * @param Request $request
     * @return string
     */
    public function student(Request $request)
    {
        // 取出一列 pluck
        // 170592  170604  170603
        $gradeId = $request->get('grade_id');
        $titleId = $request->get('evaluate_title_id');
        $gradeUserDao = new GradeUserDao;
        $recordsDao   = new EvaluateStudentRecordDao;

        $records = $recordsDao->getRecordsByTitleId($titleId);
        $userIds = $records->pluck('user_id')->toArray();

        $users        = $gradeUserDao->paginateUserByGrade($gradeId, Role::VERIFIED_USER_STUDENT);
        $data         = [];

        foreach ($users as $key => $val) {
            $data[$key]['is_comment'] =  false;
            if (in_array($val->user_id, $userIds)) {
                $data[$key]['is_comment'] =  true;
            }
            $data[$key]['user_id'] = $val->user_id;
            $data[$key]['name']    = $val->name;
        }

        return JsonBuilder::Success($data);
    }

    /**
     * 评学模板
     * @param Request $request
     * @return string
     */
    public function template(Request $request)
    {
        $user = $request->user();

        $schoolId = $user->getSchoolId();

        $dao = new EvaluateDao;

        $evaluate = $dao->getEvaluate($schoolId, Evaluate::TYPE_STUDENT);

        $data = [];

        foreach ($evaluate as $key => $val) {
            $data[$key]['evaluate_id'] = $val->id;
            $data[$key]['score']       = $val->score;
            $data[$key]['title']       = $val->title;
        }
        if (empty($data)) {
            return JsonBuilder::Error('管理员还是没添写,评学模板 请联系管理');
        }
        return JsonBuilder::Success($data);
    }

    /**
     * 评价学生
     * @param Request $request
     * @return string
     */
    public function students(Request $request)
    {
        $teacherId  = $request->user()->id;
        $userId     = $request->get('user_id');
        $record = $request->get('record');
        $desc   = $request->get('desc');
        $titleId = $request->get('evaluate_title_id');

        $dao = new EvaluateStudentRecordDao;
        $data = [];
        foreach ($record as $key => $val) {
            $data[$key]['user_id'] = $userId;
            $data[$key]['teacher_id'] = $teacherId;
            $data[$key]['desc'] = $desc;
            $data[$key]['evaluate_id'] = $val['evaluate_id'];
            $data[$key]['score'] = $val['score'];
            $data[$key]['title_id'] = $titleId;
            $data[$key]['created_at'] = Carbon::now();
        }
        $add = $dao->add($data);
        if ($add) {
            return JsonBuilder::Success('评价成功');
        } else {
            return JsonBuilder::Error('系统错误,请稍后再试');
        }
    }

    /**
     * 保存教师上传的教学业绩证明材料
     * @param Request $request
     * @return string
     */
    public function save_qualification(Request $request){
        $qualification = $request->get('qualification');
        /**
         * @var User $user
         */
        $user = $request->user('api');
        if($user){
            try{
                $qualification['uploaded_by'] = $user->id;
                $qualification['school_id'] = $user->getSchoolId();
                $id = $qualification['id'];
                if(empty($id)){
                    $bean = TeacherQualification::create($qualification);
                    $id = $bean->id;
                }else{
                    TeacherQualification::where('id',$qualification['id'])->update($qualification);
                }

                return JsonBuilder::Success(['id'=>$id]);
            }
            catch (\Exception $exception){
                return JsonBuilder::Error($exception->getMessage());
            }
        }
    }

    /**
     * 加载某个用户的业绩材料
     * @param Request $request
     * @return string
     */
    public function load_qualifications(Request $request){
        $userId = $request->get('user', null);
        if(is_null($userId)){
            $user = $request->user('api');
            $userId = $user->id ?? null;
        }
        if($userId){
            $qualifications = TeacherQualification::where('user_id',$userId)
                ->orderBy('year','desc')
                ->orderBy('id','desc')
                    ->get();
            return JsonBuilder::Success(['qualifications'=>$qualifications]);
        }
        return JsonBuilder::Error('无法定位用户');
    }
}
