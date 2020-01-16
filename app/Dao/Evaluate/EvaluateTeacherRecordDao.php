<?php


namespace App\Dao\Evaluate;


use App\Utils\JsonBuilder;
use Illuminate\Support\Facades\DB;
use App\Utils\ReturnData\MessageBag;
use App\Models\Evaluate\EvaluateTeacher;
use App\Models\Evaluate\EvaluateStudent;
use App\Models\Evaluate\EvaluateTeacherRecord;

class EvaluateTeacherRecordDao
{
    /**
     * 获取评教记录
     * @param $evalTeacherId
     * @return mixed
     */
    public function getRecordByEvalTeacherId($evalTeacherId) {
        $map = ['evaluate_teacher_id'=>$evalTeacherId];
        $list = EvaluateTeacherRecord::where($map)->get();
        return $list;
    }


    /**
     * 保存评教
     * @param $evaluate
     * @param $record
     * @param $student
     * @return MessageBag
     */
    public function create($evaluate, $record, $student) {
        $messageBag = new MessageBag();
        // 判断是否有学生已经提交过评教
        $map = [
            'user_id'=>$evaluate['user_id'],'year'=>$evaluate['year'],'type'=>$evaluate['type'],
            'school_id'=>$evaluate['school_id'],'time_slot_id'=>$evaluate['time_slot_id'],
            'weekday_index'=>$evaluate['weekday_index'],'week'=>$evaluate['week']
        ];
        $info = EvaluateTeacher::where($map)->first();

        try{
            DB::beginTransaction();
            if(is_null($info)) {
                // 评教老师主表
                $info = EvaluateTeacher::create($evaluate);
            } else {
                $map = ['evaluate_teacher_id'=>$info->id, 'user_id'=>$student['user_id']];
                // 查看当前学生是否已经评教
                $evalStudent = EvaluateStudent::where($map)->first();
                if(!is_null($evalStudent)) {
                    $return = ['id'=>$evalStudent->id, 'score'=>$evalStudent->score];
                    $messageBag->setData($return);
                    $messageBag->setMessage('评教成功');
                    DB::commit();
                    return $messageBag;
                }

            }

            // 评教学生主表
            $score = array_sum(array_column($record, 'score'));
            $student['evaluate_teacher_id'] = $info->id;
            $student['score'] = $score;
            $evalStudent = EvaluateStudent::create($student);

            foreach ($record as $key => $val) {
                $record = [
                    'evaluate_id' => $val['evaluate_id'],
                    'evaluate_student_id' => $evalStudent->id,
                    'user_id'     => $student['user_id'],
                    'score'       => $val['score'],
                ];
                EvaluateTeacherRecord::create($record);
            }
            // 更新老师评价主表的分值
            $num = $info['num'] + 1;
            $score = round(($info['score'] + $score)/$num,2);
            $save = ['score'=>$score, 'num'=>$num ];
            EvaluateTeacher::where('id', $info->id)->update($save);


            DB::commit();
            $messageBag->setMessage('评教成功');
            $messageBag->setData(['id'=>$evalStudent->id, 'score'=>$evalStudent->score]);

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('评教失败'.$msg);
        }
        return$messageBag;

    }
}
