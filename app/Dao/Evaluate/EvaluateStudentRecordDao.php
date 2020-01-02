<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateStudentRecord;

class EvaluateStudentRecordDao
{

    /**
     * 添加
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return EvaluateStudentRecord::insert($data);
    }

    /**
     * 根据评学ID 查询
     * @param $titleId
     * @return
     */
    public function getRecordsByTitleId($titleId)
    {
        return EvaluateStudentRecord::where('title_id', $titleId)->get();
    }

}
