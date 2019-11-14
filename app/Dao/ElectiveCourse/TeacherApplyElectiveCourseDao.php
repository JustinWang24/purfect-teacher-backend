<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/10/19
 * Time: 9:34 PM
 */

namespace App\Dao\ElectiveCourses;
use App\Models\ElectiveCourses\TeacherApplyElectiveCourse;
use App\Models\ElectiveCourses\TeacherApplyElectiveCoursesTimeSlot;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\UserDao;
//use App\Models\Course;
//use App\Models\Courses\CourseMajor;
//use App\Models\Courses\CourseTeacher;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
//use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
//use Ramsey\Uuid\Uuid;
//use App\Dao\BuildFillableData;

class TeacherApplyElectiveCourseDao
{
    use BuildFillableData;
    public function __construct()
    {

    }


    public function getApplyById($id)
    {
        return TeacherApplyElectiveCourse::find($id);
    }
    /**
     * 创建选修课程的方法
     * @param $data
     * @return IMessageBag
     */
    public function createTeacherApplyElectiveCourse($data){
        if(isset($data['id']) || empty($data['id'])){
            unset($data['id']);
        }
        //周，星期，时间槽数据以数组方式传入
        $timeSlots = $data['timeSlots'];
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();
        try{
            $fillableData = $this->getFillableData(new TeacherApplyElectiveCourse(),$data);

            // 先保存申请选修课程数据
            $apply = TeacherApplyElectiveCourse::create($fillableData);

            if($apply){
                // 保存课时安排
                if(!empty($timeSlots)){
                    foreach ($timeSlots as $timeSlot) {
                        $d = [
                            'teacher_apply_elective_courses_id'=>$apply->id,
                            'week'=>$timeSlot['week'],
                            'day_index'=>$timeSlot['day_index'],
                            'time_slot_id'=>$timeSlot['time_slot_id'],
                        ];
                        TeacherApplyElectiveCoursesTimeSlot::create($d);
                    }
                }

                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($apply);
            }
            else{
                DB::rollBack();
                $messageBag->setMessage('保存选修课程数据失败, 请联系管理员');
            }

        }catch (\Exception $exception){
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }

        return $messageBag;
    }

    public function updateTeacherApplyElectiveCourse($data){
        $id = $data['id'];
        unset($data['id']);

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();
        try{
            $fillableData = $this->getFillableData(new TeacherApplyElectiveCourse(),$data);

            // 先保存申请选修课程数据
            $apply = TeacherApplyElectiveCourse::where('id',$id)->update($fillableData);

            if($apply){
                //先删除旧的课时安排
                TeacherApplyElectiveCoursesTimeSlot::where('teacher_apply_elective_courses_id',$id)->delete();
                // 保存课时安排
                if(!empty($timeSlots)){
                    foreach ($timeSlots as $timeSlot) {
                        $d = [
                            'teacher_apply_elective_courses_id'=>$apply->id,
                            'week'=>$timeSlot['week'],
                            'day_index'=>$timeSlot['day_index'],
                            'time_slot_id'=>$timeSlot['time_slot_id'],
                        ];
                        TeacherApplyElectiveCoursesTimeSlot::create($d);
                    }
                }

                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($apply);
            }
            else{
                DB::rollBack();
                $messageBag->setMessage('无法更新选修课申请, 请联系管理员');
            }

        }catch (\Exception $exception){
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }

        return $messageBag;
    }


}
