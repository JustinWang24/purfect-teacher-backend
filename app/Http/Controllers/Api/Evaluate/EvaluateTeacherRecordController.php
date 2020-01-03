<?php


namespace App\Http\Controllers\Api\Evaluate;


use App\Dao\Evaluate\EvaluateDao;
use App\Dao\Evaluate\EvaluateStudentDao;
use App\Models\Evaluate\Evaluate;
use App\Models\Evaluate\EvaluateStudent;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Evaluate\EvaluateTeacherRecordDao;
use App\Http\Requests\Evaluate\EvaluateTeacherRecordRequest;

class EvaluateTeacherRecordController extends Controller
{
    /**
     * 学生提交评教
     * @param EvaluateTeacherRecordRequest $request
     * @return string
     */
    public function create(EvaluateTeacherRecordRequest $request) {
        $data = $request->getRecordData();
        $dao = new EvaluateTeacherRecordDao();
        $result = $dao->create($data);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            return JsonBuilder::Success($msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }


     /**
     * 评价教师列表
     * @param EvaluateTeacherRecordRequest $request
     * @return string
     */
    public function getTeacherList(EvaluateTeacherRecordRequest $request) {
        $data = $request->getStudentData();
        $dao = new EvaluateStudentDao();
        $list = $dao->getStudentByUserId($data['user_id'], $data['year'], $data['type']);
        $teachers = [];
        foreach ($list as $key => $val) {
            $user = $val->evaluateTeacher->user;
            $teachers[$key]['evaluate_teacher_id'] = $val->evaluate_teacher_id;
            $teachers[$key]['name'] = $user->name;
        }
        return JsonBuilder::Success($teachers);
    }


    /**
     * 模版数据
     * @param EvaluateTeacherRecordRequest $request
     * @return string
     */
    public function template(EvaluateTeacherRecordRequest $request) {
        $schoolId = $request->user()->getSchoolId();
        $dao = new EvaluateDao();
        $list = $dao->pageList($schoolId,Evaluate::TYPE_TEACHER);
        $data = pageReturn($list);
        return JsonBuilder::Success($data);
    }

    /**
     * 判断是否开启评教
     * @param EvaluateTeacherRecordRequest $request
     * @return string
     */
    public function isEvaluate(EvaluateTeacherRecordRequest $request) {
        $userId = $request->user()->id;
        $dao = new EvaluateStudentDao();
        $return = $dao->getStudentByUserAndStatus($userId,EvaluateStudent::STATUS_NOT_EVALUATE);
        if(count($return) == 0) {
            return JsonBuilder::Success(['status'=>false]);
        } else {
            return JsonBuilder::Success(['status'=>true,'list'=>$return]);
        }

    }


}
