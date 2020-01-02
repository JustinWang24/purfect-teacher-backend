<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateStudentTitle;
use Carbon\Carbon;

class EvaluateStudentTitleDao
{

    public function getEvaluateTitleBySchoolId($schoolId)
    {
        return EvaluateStudentTitle::where('school_id', $schoolId)
            ->where('status', EvaluateStudentTitle::STATUS_START)
            ->orderBy('id', 'desc')
            ->first();
    }

}
