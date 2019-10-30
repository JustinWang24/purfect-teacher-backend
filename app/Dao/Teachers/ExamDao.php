<?php
namespace App\Dao\Teachers;

use App\Models\Teachers\Exam;
use App\Models\Teachers\ExamsRoom;
use Illuminate\Support\Facades\DB;

class ExamDao
{

    /**
     * 创建考试
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return Exam::create($data);
    }


    /**
     * 获取考试列表
     * @param $map
     * @return mixed
     */
    public function getExams($map)
    {
        return Exam::where($map)->with(['course','examsRoom.room'])->get();
    }


    /**
     * 修改考试
     * @param $map
     * @param $data
     * @return mixed
     */
    public function updExam($map,$data)
    {
        return Exam::where($map)->update($data);
    }


    /**
     * 创建考场
     * @param $data
     * @return mixed
     */
    public function createExamRooms($data)
    {
        return ExamsRoom::create($data);
    }


    /**
     * 创建考试逻辑
     * @param $data
     * @return array
     */
    public function addExam($data)
    {
        try{
            DB::beginTransaction();

            // 添加考试
            $s1 = $this->create($data);
            if(!$s1->id)
            {
                throw new \Exception('创建考试失败');
            }

            foreach ($data['room_id'] as $key => $val) {
                $examRoomData['exam_id']   = $s1->id;
                $examRoomData['room_id']   = $val;
                $examRoomData['exam_time'] = $data['exam_time'];
                $examRoomData['from']      = $data['from'];
                $examRoomData['to']        = $data['to'];

                // 添加考场
                $s2 = $this->createExamRooms($examRoomData);
                if(!$s2->id)
                {
                    throw new \Exception('创建考场失败');
                }
            }

            DB::commit();
            return ['code'=>1,'msg'=>'创建成功'];
        }catch (\Exception $e){
            DB::rollBack();
            $msg = $e->getMessage();
            return ['code'=>0,'msg'=>$msg];
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
