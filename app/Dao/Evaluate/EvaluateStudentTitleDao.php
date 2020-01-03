<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateStudentTitle;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;

class EvaluateStudentTitleDao
{

    /**
     * 根据ID 获取一条
     * @param $id
     * @return mixed
     */
    public function getEvaluateTitleById($id)
    {
        return EvaluateStudentTitle::where('id', $id)->first();
    }



    /**
     * 根据学校ID 获取一条
     * @param $schoolId
     * @return mixed
     */
    public function getEvaluateTitleBySchoolId($schoolId)
    {
        return EvaluateStudentTitle::where('school_id', $schoolId)
            ->where('status', EvaluateStudentTitle::STATUS_START)
            ->first();
    }

    /**
     * 根据学校ID 分页查询
     * @param $schoolId
     * @return mixed
     */
    public function getEvaluateTitlePageBySchoolId($schoolId)
    {
        return EvaluateStudentTitle::where('school_id', $schoolId)
            ->orderBy('id', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * 添加
     * @param $data
     * @return MessageBag
     */
    public function create($data)
    {
        $messageBag = new MessageBag;
        $result =  EvaluateStudentTitle::create($data);
        if($result) {
            $messageBag->setMessage('创建成功');
            return $messageBag;
        } else {
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('创建失败');
            return $messageBag;
        }
    }


    /**
     * 编辑
     * @param $id
     * @param $data
     * @return mixed
     */
    public function editEvaluateTitleById($id, $data)
    {
        return EvaluateStudentTitle::where('id', $id)->update($data);
    }


    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function deleteEvaluateTitle($id)
    {
        return EvaluateStudentTitle::where('id', $id)->delete();
    }

}
