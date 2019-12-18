<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/12/19
 * Time: 1:46 PM
 */

namespace App\Dao\Evaluation;
use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Models\Schools\SchoolConfiguration;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use App\Utils\Time\GradeAndYearUtil;
use Illuminate\Support\Facades\DB;
use App\Models\Evaluation\RateTeacherSummary;
use App\Models\Evaluation\RateTeacherDetail;

class RateTeacherDao
{
    /**
     * @param $itemId
     * @param $studentId
     * @return RateTeacherDetail
     */
    public function getByTimetableItemAndUser($itemId, $studentId){
        return RateTeacherDetail::where('timetable_item_id',$itemId)
            ->where('student_id', $studentId)
            ->first();
    }

    public function getSummaryByTeacher($teacherId){
        return RateTeacherSummary::where('teacher_id',$teacherId)->first();
    }

    /**
     * @param User $student
     * @param $data
     * @param $timetableItemId
     * @param $teacherId
     * @param $courseId
     * @return IMessageBag
     */
    public function rateTeacher(User $student, $data, $timetableItemId, $teacherId, $courseId){
        DB::beginTransaction();
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        try{
            $dao = new SchoolDao();
            $school = $dao->getSchoolById($student->getSchoolId());

            /**
             * @var SchoolConfiguration $config
             */
            $config = $school->configuration;
            $scheduleWeek = $config->getScheduleWeek(now(GradeAndYearUtil::TIMEZONE_CN));
            $scheduleWeekIndex = $scheduleWeek ? $scheduleWeek->getScheduleWeekIndex() : 0;

            $timetableItem = (new TimetableItemDao())->getItemById($timetableItemId);
            $avg = ($data['prepare'] + $data['material']
                + $data['on_time'] + $data['positive'] + $data['result'])/5;
            $detail = RateTeacherDetail::create([
                'year'=>$timetableItem->year,
                'term'=>$timetableItem->term,
                'teacher_id'=>$teacherId,
                'student_id'=>$student->id,
                'course_id'=>$courseId,
                'timetable_item_id'=>$timetableItemId,
                'calendar_week_number'=>$scheduleWeekIndex,
                'average_points'=>$avg,
                'prepare'=>$data['prepare'],
                'material'=>$data['material'],
                'on_time'=>$data['on_time'],
                'positive'=>$data['positive'],
                'result'=>$data['result'],
                'comment'=>$data['comment'],
            ]);

            $summary = $this->getSummaryByTeacher($teacherId);
            if(!$summary){
                $summary = new RateTeacherSummary();
                $summary->year = $detail->year;
                $summary->term = $detail->term;
                $summary->teacher_id = $detail->teacher_id;
            }
            $summary->reCalculate($detail);
            $summary->save();

            DB::commit();
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            $bag->setData($detail);
        }
        catch (\Exception $exception){
            $bag->setMessage($exception->getMessage(). ' Line: ' . $exception->getLine());
            DB::rollBack();
        }
        return $bag;
    }
}