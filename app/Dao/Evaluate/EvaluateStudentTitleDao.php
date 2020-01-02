<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateStudentTitle;
use App\Utils\Misc\ConfigurationTool;

class EvaluateStudentTitleDao
{

    public function getEvaluateTitleBySchoolId($schoolId)
    {
        return EvaluateStudentTitle::where('school_id', $schoolId)
            ->where('status', EvaluateStudentTitle::STATUS_START)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getEvaluateTitlePageBySchoolId($schoolId)
    {
        return EvaluateStudentTitle::where('school_id', $schoolId)
            ->orderBy('id', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

}
