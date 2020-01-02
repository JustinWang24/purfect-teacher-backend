<?php


namespace App\Dao\Evaluate;


use App\Utils\JsonBuilder;
use App\Models\Teachers\Teacher;
use Illuminate\Support\Facades\DB;
use App\Utils\ReturnData\MessageBag;
use App\Utils\Misc\ConfigurationTool;
use App\Models\Evaluate\EvaluateStudent;
use App\Models\Evaluate\EvaluateTeacher;


class EvaluateTeacherDao
{

    /**
     * @param object $teachers 老师
     * @param int    $year     学年
     * @param int    $type     学期
     * @param int    $schoolId 学校ID
     * @param array  $users    学生ID
     * @param int    $gradeId  班级ID
     * @return MessageBag
     */
    public function create($teachers, $year, $type, $schoolId, $users, $gradeId) {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        try {
            DB::beginTransaction();
            foreach ($teachers as $key => $val) {

                // 查询当前学年学期的老师的总表是否创建
                $map = ['user_id'=>$val->teacher_id, 'year'=>$year, 'type'=>$type];
                $result = EvaluateTeacher::where($map)->first();
                if(empty($result)) {

                    $evaluateTeacher = [
                        'school_id' => $schoolId,
                        'user_id' => $val->teacher_id,
                        'year' => $year,
                        'type' => $type,
                    ];
                    $result = EvaluateTeacher::create($evaluateTeacher);
                }
                foreach ($users as $k => $v) {
                    $student = [
                        'evaluate_teacher_id' => $result->id,
                        'user_id' => $v,
                        'grade_id'=> $gradeId,
                        'year'=>$year,
                        'type'=>$type
                    ];
                    EvaluateStudent::create($student);
                }
            }

            DB::commit();
            $messageBag->setMessage('创建成功');
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            return $messageBag;

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('创建失败'.$msg);
            return $messageBag;
        }
    }


    /**
     * 查询评教列表
     * @param $schoolId
     * @param null $year
     * @param null $type
     * @return mixed
     */
    public function getEvaluateTeacherList($schoolId, $year=null, $type=null) {
        $map = ['school_id'=>$schoolId];
        if(!is_null($year)) {
            $map['year'] = $year;
        }
        if(!is_null($type)) {
            $map['type'] = $type;
        }
        return EvaluateTeacher::where($map)
            ->orderBy('year','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
}
