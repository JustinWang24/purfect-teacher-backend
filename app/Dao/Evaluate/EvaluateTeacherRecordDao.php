<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateTeacher;
use App\Models\Evaluate\EvaluateTeacherRecord;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

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
            foreach ($data['record'] as $key => $val) {

                // 判断当前学生是否已提交对该老师的评价
                $map = ['evaluate_teacher_id'=>$data['evaluate_teacher_id'], 'user_id'=>$data['user_id']];
                $re = EvaluateTeacherRecord::where($map)->first();
                if(is_null($re)) {
                    $record = [
                        'evaluate_id' => $val['evaluate_id'],
                        'evaluate_teacher_id' => $data['evaluate_teacher_id'],
                        'user_id'     => $data['user_id'],
                        'grade_id'    => $data['grade_id'],
                        'score'       => $val['score'],
                        'desc'        => $data['desc']
                    ];
                    EvaluateTeacherRecord::create($record);
                    // 更新老师评价主表的分值
                    $teacher = EvaluateTeacher::where('id',$data['evaluate_teacher_id'])->first();
                    $num = $teacher['num'] + 1;
                    $score = round(($teacher['score'] + $val['score'])/$num,2);
                    $save = ['score'=>$score, 'num'=>$num ];
                    EvaluateTeacher::where('id', $data['evaluate_teacher_id'])->update($save);
                }
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
