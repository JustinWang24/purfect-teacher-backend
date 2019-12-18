<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/12/19
 * Time: 1:46 PM
 */

namespace App\Dao\Evaluation;
use App\Dao\Schools\SchoolDao;
use App\Models\Schools\SchoolConfiguration;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Models\Evaluation\RateTeacherSummary;
use App\Models\Evaluation\RateTeacherDetail;

class RateTeacherDao
{
    /**
     * @param User $student
     * @param $data
     * @param $tiemtableItemId
     * @param $teacherId
     * @param $courseId
     * @return RateTeacherDetail
     */
    public function rateTeacher(User $student, $data, $tiemtableItemId, $teacherId, $courseId){
        DB::beginTransaction();
        $detail = null;
        try{
            $dao = new SchoolDao();
            $school = $dao->getSchoolById($student->getSchoolId());

            /**
             * @var SchoolConfiguration $config
             */
            $config = $school->configuration;
        }
        catch (\Exception $exception){

        }

        return $detail;
    }
}