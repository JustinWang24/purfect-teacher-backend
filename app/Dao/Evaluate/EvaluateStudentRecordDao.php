<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateStudentRecord;

class EvaluateStudentRecordDao
{

    public function add($data)
    {
        return EvaluateStudentRecord::create($data);
    }

}
