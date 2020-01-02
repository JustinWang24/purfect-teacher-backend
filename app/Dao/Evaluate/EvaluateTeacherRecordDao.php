<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateTeacherRecord;

class EvaluateTeacherRecordDao
{
    /**
     * 获取评教记录
     * @param $evalTeacherId
     * @return mixed
     */
    public function getRecordByEvalTeacherId($evalTeacherId) {
        $map = ['evaluate_teacher_id'=>$evalTeacherId];
        $list = EvaluateTeacherRecord::where($map)->get();
        return $list;
    }


    /**
     * @param $data
     * @return mixed
     */
    public function create($data) {
        return EvaluateTeacherRecord::create($data);
    }
}
