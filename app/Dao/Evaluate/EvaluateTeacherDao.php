<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateTeacher;
use App\Models\Teachers\Teacher;
use Illuminate\Support\Facades\DB;

class EvaluateTeacherDao
{

    public function create($teachers, $year, $type, $schoolId, $students) {

        dd($students);
        try {
            DB::beginTransaction();
            foreach ($teachers as $key => $val) {
            /**
             * @var Teacher $teacher
             */
            $re = Teacher::myTeachingAndResearchGroup($val->teacher_id);
            $evaluateTeacher = [
                'school_id' => $schoolId,
                'user_id' => $val->teacher_id,
                'year' => $year,
                'type' => $type,
                'group_id' => $re[0]->id,
            ];

            $result = EvaluateTeacher::create($evaluateTeacher);

            foreach ($students as $k => $v) {
                $record = [

                ];
            }
        }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
