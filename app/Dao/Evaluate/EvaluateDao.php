<?php


namespace App\Dao\Evaluate;


use App\Utils\JsonBuilder;
use App\Models\Evaluate\Evaluate;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;

class EvaluateDao
{

    /**
     * 创建评价内容
     * @param $data
     * @return MessageBag
     */
    public function create($data) {
        $messageBag = new MessageBag();
        $re = $this->getEvaluateByTitle($data['title'], $data['school_id']);
        if(!empty($re)) {
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('该标题已存在,请重新更换标题');
            return $messageBag;
        }
        $result = Evaluate::create($data);
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
     * 根据title查询
     * @param $title
     * @param $schoolId
     * @return mixed
     */
    public function getEvaluateByTitle($title, $schoolId) {
        $map = ['school_id'=>$schoolId, 'title'=>$title];
        return Evaluate::where($map)->first();
    }


    /**
     * 分页查询
     * @param $schoolId
     * @param $type
     * @return mixed
     */
    public function pageList($schoolId, $type = null) {
        $map = ['school_id' => $schoolId];
        if (!is_null($type)) {
            $map['type'] = $type;
        }
        $list = Evaluate::where($map)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        return $list;
    }

    /**
     * 获取全部评价模板
     * @param $schoolId
     * @param $type
     * @return mixed
     */
    public function getEvaluate($schoolId, $type)
    {
        $map = ['school_id' => $schoolId, 'type' => $type];
        return Evaluate::where($map)->orderBy('created_at', 'desc')->get();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getEvaluateById($id) {
        $map = ['id'=>$id];
        return Evaluate::where($map)->first();
    }

    /**
     * 编辑
     * @param $id
     * @param $data
     * @return mixed
     */
    public function editEvaluateById($id, $data) {
        return Evaluate::where('id', $id)->update($data);
    }


    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function deleteEvaluate($id) {
        return Evaluate::where('id', $id)->delete();
    }
}
