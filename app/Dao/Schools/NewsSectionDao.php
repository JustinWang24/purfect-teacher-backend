<?php


namespace App\Dao\Schools;


use App\Models\Schools\NewsSection;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Database\Eloquent\Collection;
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
            $lastSection = $this->getLast($data['news_id']);
            $lastPosition = $lastSection ? $lastSection->position : 0;

            $theLastId = null;
            foreach ($data['sections'] as $key => $val) {
                $val['news_id'] = $data['news_id'];
                $lastPosition++;
                $val['position'] = $lastPosition;
                $sec = NewsSection::create($val);
                $theLastId = $sec->id;
            }
            DB::commit();
            $bag = new MessageBag(JsonBuilder::CODE_SUCCESS, '创建成功');
            if(count($data['sections']) === 1){
                $bag->setData($theLastId);
            }
            return $bag;
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
     * @return NewsSection
     */
    public function getSectionById($id) {
        return NewsSection::where('id', $id)->first();
    }


    /**
     * 通过newsId查询
     * @param $newsId
     * @return Collection
     */
    public function getSectionByNewsId($newsId) {
        return NewsSection::whereIn('news_id', $newsId)->get();
    }

    /**
     * 段落上移
     * @param $id
     * @return bool
     */
    public function moveUp($id){
        $section = $this->getSectionById($id);

        $currentSectionPosition = $section->position;

        $prev = $this->getPrev($section);

        $result = false;
        if($prev){
            $prevPosition = $prev->position;
            DB::beginTransaction();
            try{
                $prev->position = $currentSectionPosition;
                $prev->save();
                $section->position = $prevPosition;
                $section->save();
                DB::commit();
                $result = true;
            }
            catch (\Exception $exception){
                DB::rollBack();
            }
        }
        return $result;
    }

    /**
     * 段落下移
     * @param $id
     * @return bool
     */
    public function moveDown($id){
        $section = $this->getSectionById($id);

        $currentSectionPosition = $section->position;

        $next = $this->getNext($section);

        $result = false;
        if($next){
            $nextPosition = $next->position;
            DB::beginTransaction();
            try{
                $next->position = $currentSectionPosition;
                $next->save();
                $section->position = $nextPosition;
                $section->save();
                DB::commit();
                $result = true;
            }
            catch (\Exception $exception){
                DB::rollBack();
            }
        }
        return $result;
    }

    /**
     * 获取前一个段落
     * @param NewsSection $section
     * @return NewsSection
     */
    public function getPrev($section){
        return NewsSection::where('position','<',$section->position)
            ->where('news_id',$section->news_id)
            ->orderBy('position','desc')
            ->first();
    }

    /**
     * 获取下一个段落
     * @param NewsSection $section
     * @return NewsSection
     */
    public function getNext($section){
        return NewsSection::where('position','>',$section->position)
            ->where('news_id',$section->news_id)
            ->orderBy('position','asc')
            ->first();
    }

    /**
     * 获取最后一个段落
     * @param int $newsId
     * @return NewsSection
     */
    public function getLast($newsId){
        return NewsSection::where('news_id',$newsId)
            ->orderBy('position','desc')
            ->first();
    }
}
