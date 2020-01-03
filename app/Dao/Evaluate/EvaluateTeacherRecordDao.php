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
     * 创建
     * @param $data
     * @return MessageBag
     */
    public function create($data) {
        $messageBag = new MessageBag();
        try{
            DB::beginTransaction();
            $teacher = EvaluateTeacher::where('id',$data['evaluate_teacher_id'])->first();

            // 判断当前学生是否已提交对该老师的评价
            $map = ['status'=>EvaluateStudent::STATUS_EVALUATE, 'user_id'=>$data['user_id'],
                'year'=>$teacher['year'], 'type'=>$teacher['type']];
            $re = EvaluateStudent::where($map)->first();
            if(is_null($re)) {
                $score = 0;

                foreach ($data['record'] as $key => $val) {
                    $score += $val['score'];
                    $record = [
                        'evaluate_id' => $val['evaluate_id'],
                        'evaluate_teacher_id' => $data['evaluate_teacher_id'],
                        'user_id'     => $data['user_id'],
                        'grade_id'    => $data['grade_id'],
                        'score'       => $val['score'],
                    ];
                    EvaluateTeacherRecord::create($record);
                }
                // 修改学生评教状态
                $map = ['evaluate_teacher_id'=>$data['evaluate_teacher_id'],'user_id'=>$data['user_id']];
                $update = ['status'=>EvaluateStudent::STATUS_EVALUATE, 'score'=>$score, 'desc'=>$data['desc']];
                EvaluateStudent::where($map)->update($update);

                 // 更新老师评价主表的分值
                $num = $teacher['num'] + 1;
                $score = round(($teacher['score'] + $score)/$num,2);
                $save = ['score'=>$score, 'num'=>$num ];
                EvaluateTeacher::where('id', $data['evaluate_teacher_id'])->update($save);

            }

            DB::commit();
            $messageBag->setMessage('评教成功');
            return $messageBag;

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('评教失败'.$msg);
            return$messageBag;
        }

    }
}
