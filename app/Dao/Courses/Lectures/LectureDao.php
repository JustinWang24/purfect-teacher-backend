<?php
/**
 * 课件的DAO
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Dao\Courses\Lectures;
use App\Dao\Users\GradeUserDao;
use App\Models\Courses\Homework;
use App\Models\Courses\Lecture;
use App\Models\Courses\LectureMaterial;
use App\Models\Users\GradeUser;
use App\Utils\ReturnData\MessageBag;
use App\Utils\JsonBuilder;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class LectureDao
{
    /**
     * @param $lectureId
     * @return Lecture
     */
    public function getLectureById($lectureId){
        return Lecture::find($lectureId);
    }

    /**
     * @param $courseId
     * @param $teacherId
     * @param $index
     * @return Lecture
     */
    public function getLecturesByCourseAndTeacherAndIndex($courseId, $teacherId,$index){
        $lecture = Lecture::where('course_id',$courseId)
            ->where('teacher_id',$teacherId)
            ->where('idx',$index)
            ->first();
        if(!$lecture){
            $lecture = Lecture::create([
                'course_id'=>$courseId,
                'teacher_id'=>$teacherId,
                'idx'=>$index,
                'title'=>'',
                'summary'=>'',
                'tags'=>'',
            ]);
        }
        return $lecture;
    }

    /**
     * @param $courseId
     * @param $teacherId
     * @return Collection
     */
    public function getLecturesByCourseAndTeacher($courseId, $teacherId){
        return Lecture::where('course_id',$courseId)
            ->where('teacher_id',$teacherId)
            ->orderBy('idx','asc')
            ->get();
    }

    /**
     * 根据课节的id获取其所有课件附件的记录
     * @param $lectureId
     * @return Collection
     */
    public function getLectureMaterials($lectureId){
        return LectureMaterial::where('lecture_id',$lectureId)
            ->orderBy('type','asc')
            ->get();
    }

    /**
     * @param $lectureId
     * @param $grades
     * @return Collection
     */
    public function getLectureHomework($lectureId, $grades){
        $result = new Collection();
        if($grades){
            $gradeStudents = (new GradeUserDao())->getGradeUserWhereInGrades($grades);
            $studentsIds = [];
            foreach ($gradeStudents as $gradeStudent) {
                /**
                 * @var GradeUser $gradeStudent
                 */
                if($gradeStudent->isStudent()){
                    $studentsIds[] = $gradeStudent->user_id;
                }
            }
            $yearAndTerm = GradeAndYearUtil::GetYearAndTerm(Carbon::now());
            $result = Homework::where('year', $yearAndTerm['year'])
                ->where('lecture_id',$lectureId)
                ->whereIn('student_id',$studentsIds)
                ->orderBy('id','desc')
                ->get();
        }
        return $result;
    }

    /**
     * 学生获取自己某节课的作业
     * @param $studentId
     * @param $courseId
     * @param $idx
     * @param $year
     * @return Collection
     */
    public function getHomeworkByStudentAndLectureAndYear($studentId, $courseId, $idx, $year){
        return Homework::where('year', $year)
            ->where('course_id',$courseId)
            ->where('idx',$idx)
            ->where('student_id',$studentId)
            ->orderBy('id','desc')
            ->get();
    }

    /**
     * @param $data
     * @return Homework
     */
    public function saveHomework($data){
        return Homework::create($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteHomework($id){
        $homework = Homework::find($id);
        $filePath = $homework->url;
        if($filePath){
            $file = str_replace(env('APP_URL').'/storage','',$filePath);
            unlink(storage_path('app/public').$file);
        }
        return $homework->delete();
    }

    public function getLectureMaterial($materialId){
        return LectureMaterial::find($materialId);
    }

    /**
     * 更新课件的记录，注意这个方法只会更新title和summary这两个字段
     * @param $data
     * @return mixed
     */
    public function updateLectureSummary($data){
        return Lecture::where('id',$data['id'])->update($data);
    }

    /**
     * 保存某个课节的附件材料
     * @param $data
     * @return MessageBag
     */
    public function saveLectureMaterial($data){
        $bag = new MessageBag();
        try{
            if(empty($data['id'])){
                $material =  LectureMaterial::create($data);
                $bag->setData($material);
            }
            else{
                LectureMaterial::where('id',$data['id'])
                    ->update($data);
                $material =  LectureMaterial::find($data['id']);
                $bag->setData($material);
            }
        }catch (\Exception $exception){
            $bag->setCode(JsonBuilder::CODE_ERROR);
            $bag->setMessage($exception->getMessage());
        }
        return $bag;
    }
}