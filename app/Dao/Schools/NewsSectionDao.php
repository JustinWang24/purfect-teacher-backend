<?php


namespace App\Dao\Schools;


use App\Models\Schools\NewsSection;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class NewsSectionDao
{

    /**
     * 批量添加
     * @param $data
     * @return MessageBag
     */
    public function batchCreate($data) {
        DB::beginTransaction();
        try {
            foreach ($data['sections'] as $key => $val) {
                $val['news_id'] = $data['news_id'];
                NewsSection::create($val);
            }
            DB::commit();
            return new MessageBag(JsonBuilder::CODE_SUCCESS, '创建成功');
        }
        catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return new MessageBag(JsonBuilder::CODE_ERROR, '创建失败'.$msg);
        }
    }


    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        return NewsSection::where('id', $id)->delete();
    }


    /**
     * 编辑
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateById($id, $data) {
        return NewsSection::where('id', $id)->update($data);
    }

    /**
     * 查询
     * @param $id
     * @return mixed
     */
    public function getSectionById($id) {
        return NewsSection::where('id', $id)->first();
    }


    /**
     * 通过newsId查询
     * @param $newsId
     * @return mixed
     */
    public function getSectionByNewsId($newsId) {
        return NewsSection::whereIn('news_id', $newsId)->get();
    }
}
