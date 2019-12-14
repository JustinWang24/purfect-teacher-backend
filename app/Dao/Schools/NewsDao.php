<?php


namespace App\Dao\Schools;


use App\Models\Schools\NewsSection;
use App\Utils\JsonBuilder;
use App\Models\Schools\News;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class NewsDao
{
    public function getById($id){
        return News::where('id',$id)->with('sections')->first();
    }

    /**
     * 创建标题
     * @param $data
     * @return IMessageBag
     */
    public function create($data) {
        $info = $this->getNewByTitleAndSchoolId($data['title'], $data['school_id']);
        if(!empty($info)) {
            return new MessageBag(JsonBuilder::CODE_ERROR, '请勿重复添加');
        }
        $re = News::create($data);
        if($re) {
            $msgBag = new MessageBag(JsonBuilder::CODE_SUCCESS, '创建成功');
            $msgBag->setData($re);
            return $msgBag;
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR, '创建失败');
        }
    }


    /**
     * 通过title和schoolId查询
     * @param $title
     * @param $schoolId
     * @return mixed
     */
    public function getNewByTitleAndSchoolId($title, $schoolId) {
        $map = ['school_id'=>$schoolId, 'title'=>$title];
        return News::where($map)->first();
    }



    /**
     * 修改
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateNewById($id, $data) {
        return News::where('id', $id)->update($data);
    }


    /**
     * 删除
     * @param $id
     * @return MessageBag
     */
    public function delete($id) {
        DB::beginTransaction();
        try{
            News::where('id', $id)->delete();

            NewsSection::where('news_id', $id)->delete();

            DB::commit();
            return new MessageBag(JsonBuilder::CODE_SUCCESS, '删除成功');
        }
        catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return new MessageBag(JsonBuilder::CODE_ERROR, '删除失败'.$msg);
        }
    }

    /**
     * 获取指定类型的分页文章
     * @param $type
     * @param $schoolId
     * @return mixed
     */
    public function paginateByType($type, $schoolId){
        return News::where('type',$type)
            ->where('school_id',$schoolId)
            ->orderBy('id','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * 根据学校获取新闻动态
     * @param $schoolId
     * @param $pageNum
     * @return mixed
     */
    public function getNewBySchoolId($schoolId, $pageNum = null)
    {
        $where = ['school_id' => $schoolId, 'type' => News::TYPE_NEWS, 'publish' => News::PUBLISH_YES];
        $page = $pageNum ?? ConfigurationTool::DEFAULT_PAGE_SIZE;
        return News::where($where)->select('id', 'type', 'title', 'tags', 'created_at')->orderBy('created_at', 'desc')->paginate($page);
    }
}
