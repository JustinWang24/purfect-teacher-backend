<?php
namespace App\Dao\Teachers;

use App\Dao\Schools\RoomDao;
use App\Models\Schools\Room;
use App\Models\Teachers\Exam;
use App\Models\Teachers\ExamsPlan;
use App\Models\Teachers\ExamsPlansRoom;
use Illuminate\Support\Facades\DB;

class ExamDao
{

    use IsValid;

    /**
     * 创建考试
     * @param $data
     * @return mixed
     */
    public function create($data) {
        return Exam::create($data);
    }


    /**
     * 获取考试列表
     * @param $map
     * @return mixed
     */
    public function getExams($map) {
        return Exam::where($map)->with(['course','examsRoom.room'])->get();
    }


    /**
     * 修改考试
     * @param $map
     * @param $data
     * @return mixed
     */
    public function updExam($map,$data) {

        return Exam::where($map)->update($data);
    }


    /**
     * 创建考场
     * @param $data
     * @return mixed
     */
    public function createExamPlanRoom($data) {
        return ExamsPlansRoom::create($data);
    }


    /**
     * 获取考场详情
     * @param $map
     * @return mixed
     */
    public function getExamPlanRoomInfo($map) {
        return ExamsPlansRoom::where($map)->first();
    }


    /**
     * 修改考点信息
     * @param $map
     * @param $data
     * @return mixed
     */
    public function updExamPlanRoom($map,$data) {
        return ExamsPlansRoom::where($map)->update($data);
    }
    /**
     * 创建考试计划
     * @param $data
     * @return mixed
     */
    public function createPlan($data) {
        return ExamsPlan::create($data);
    }


    /**
     * 查寻考试计划
     * @param $map
     * @param $field
     * @return mixed
     */
    public function getExamPlanInfo($map,$field='*') {
        return ExamsPlan::where($map)->select($field)->first();
    }


    /**
     * @param $map
     * @param $roomId
     * @param string $field
     * @return mixed
     */
    public function getExamPlans($map,$roomId = [],$field='*') {
        $query = ExamsPlansRoom::where($map);

        if(!empty($roomId))
        {
            $query = $query->whereIn('room_id',$roomId);
        }

        return $query->select($field)->get();
    }


    /**
     * 查询空闲的教室
     * @param $campusId
     * @param $from
     * @param $to
     * @param $user
     * @return mixed
     */
    public function getLeisureRoom($campusId,$from,$to,$user) {

        $roomDao = new RoomDao($user);
        $roomField = ['id', 'name'];
        $map = ['campus_id'=>$campusId,'type'=>Room::TYPE_CLASSROOM];
        $list = $roomDao->getRooms($map,$roomField)->toArray();
        $roomId = array_column($list,'id');
        // todo 调取王哥接口获取当前时间上课的教室 参数 校区ID  时间 课程ID


        //查询当前时间有考试的教室
        $field = ['id','room_id'];
        $map = [['from','<=',$from],['to','>=',$to]];
        $re = $this->getExamPlans($map,$roomId,$field)->toArray();
        $planRoomId = array_column($re,'room_id');

        //最终空闲的教室ID
        $roomId = array_diff($roomId,$planRoomId);
        $roomList = $roomDao->getRoomsByIdArr($roomId,$roomField)->toArray();
        return $roomList;
    }



    /**
     * 创建考点
     * @param $roomIdArr
     * @param $planId
     * @param $user
     * @return array
     */
    public function createPlanRoom($roomIdArr, $planId, $user) {

        $map = ['id'=>$planId];
        $field = ['id', 'from', 'to'];
        $planInfo =  $this->getExamPlanInfo($map,$field)->toArray();

        $roomDao = new RoomDao($user);
        $field = ['id', 'name', 'exam_seats'];
        $roomList = $roomDao->getRoomsByIdArr($roomIdArr,$field)->toArray();
        $examSeats = array_column($roomList,'exam_seats','id');

        // 单独判断教室是否空闲
        $roomStatus = [];
        foreach ($examSeats as $key => $val) {
            $isLeisure = $this->isLeisure('room',$key,$planInfo['from'],$planInfo['to']);
            if(!$isLeisure) {
                $roomStatus[$key] = $isLeisure;
            }
        }
        if(!empty($roomStatus)) {
            $nameArr = array_column($roomList,'name','id');
            $msg = [];
            foreach ($roomStatus as $key => $val ) {
                $msg[] = $nameArr[$key].'被占,请重新选择';
            }
            $msg = implode('!',$msg);
            return ['code'=>0,'msg'=>$msg];
        }

        try{
            DB::beginTransaction();
            foreach ($examSeats as $key => $val) {
                $planRoom = [
                    'room_id' => $key,
                    'plan_id' => $planInfo['id'],
                    'from'    => $planInfo['from'],
                    'to'      => $planInfo['to'],
                    'num'     => $val
                ];

                $re = $this->createExamPlanRoom($planRoom);
                if(!$re->id) {
                    throw new \Exception('创建考场失败');
                }

            }
            DB::commit();
            return ['code'=>1,'msg'=>'创建成功'];

        }catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return ['code'=>0,'msg'=>$msg];
        }

    }


    /**
     * 考点绑定老师
     * @param $requestData
     * @return array
     */
    public function roomBindingTeacher($requestData) {
        //查询该考点是否已经确定老师
        $map = ['id'=>$requestData['plan_room_id']];
        $info = $this->getExamPlanRoomInfo($map);
        //判断当前老师是否有空
        $first = true; $second = true; $thirdly = true;
        if(!empty($requestData['first_teacher_id'])) {
            $first = $this->isLeisure('teacher',$requestData['first_teacher_id'],$info['from'],$info['to']);
        }
        if(!empty($requestData['second_teacher_id'])) {
            $second = $this->isLeisure('teacher',$requestData['second_teacher_id'],$info['from'],$info['to']);
        }
        if(!empty($requestData['thirdly_teacher_id'])) {
            $thirdly = $this->isLeisure('teacher',$requestData['thirdly_teacher_id'],$info['from'],$info['to']);
        }


        if(!$first && !$second && !$thirdly) {
            $msg = $requestData['first_invigilate'].'、'.$requestData['second_invigilate'].'、'.
                $requestData['thirdly_invigilate'].'监考老师没有空余时间，请重新选择';
            return ['code'=>0,'msg'=>$msg];
        }

        if(!$first && !$second ) {
            $msg = $requestData['first_invigilate'].'、'.$requestData['second_invigilate'].
                '监考老师没有空余时间，请重新选择';
            return ['code'=>0,'msg'=>$msg];
        }

        if( !$second && !$thirdly) {
            $msg = $requestData['second_invigilate'].'、'. $requestData['thirdly_invigilate'].
                '监考老师没有空余时间，请重新选择';
            return ['code'=>0,'msg'=>$msg];
        }

        if(!$first  && !$thirdly) {
            $msg = $requestData['first_invigilate']. '、'.$requestData['thirdly_invigilate'].
                '监考老师没有空余时间，请重新选择';
            return ['code'=>0,'msg'=>$msg];
        }

        if(!$first) {
            $msg = $requestData['first_invigilate'].'监考老师没有空余时间，请重新选择';
            return ['code'=>0,'msg'=>$msg];
        }

        if(!$second) {
            $msg = $requestData['second_invigilate'].'监考老师没有空余时间，请重新选择';
            return ['code'=>0,'msg'=>$msg];
        }

        if(!$thirdly) {
            $msg = $requestData['thirdly_invigilate'].'监考老师没有空余时间，请重新选择';
            return ['code'=>0,'msg'=>$msg];
        }

        $map = ['id'=>$requestData['plan_room_id']];
        unset($requestData['plan_room_id']);

        $re = $this->updExamPlanRoom($map,$requestData);
        if($re === false) {
            return ['code'=>0,'msg'=>'考点绑定老师失败'];
        } else {
            return ['code'=>1,'msg'=>'考点绑定老师成功'];
        }

    }




    public function getTypeAndFormalism()
    {
        $type = [
            ['id'=>Exam::TYPE_MIDTERM, 'val'=>Exam::TYPE_MIDTERM_TEXT],
            ['id'=>Exam::TYPE_FINAL, 'val'=>Exam::TYPE_FINAL_TEXT],
            ['id'=>Exam::TYPE_FOLLOW, 'val'=>Exam::TYPE_FOLLOW_TEXT],
            ['id'=>Exam::TYPE_REPAIR, 'val'=>Exam::TYPE_REPAIR_TEXT],
            ['id'=>Exam::TYPE_POSTPONE, 'val'=>Exam::TYPE_POSTPONE_TEXT],
            ['id'=>Exam::TYPE_CLEAR, 'val'=>Exam::TYPE_CLEAR_TEXT],
        ];
        $formalism = [
            ['id'=>Exam::FORMALISM_WRITTEN, 'val'=>Exam::FORMALISM_WRITTEN_TEXT],
            ['id'=>Exam::FORMALISM_COMPUTER, 'val'=>Exam::FORMALISM_COMPUTER_TXT],
        ];

        return ['type'=>$type,'formalism'=>$formalism];
    }

}
