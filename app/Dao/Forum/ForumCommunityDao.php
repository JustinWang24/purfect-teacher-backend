<?php


namespace App\Dao\Forum;


use App\Models\Forum\Community;
use App\Models\Forum\Community_member;
use App\Models\Forum\ForumType;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class ForumCommunityDao
{

    public function createCommunity($data)
    {
        $re =  Community::create($data);
        if($re){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'添加成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'添加失败');
        }
    }

    public function getCommunities($schoolId, $pageSize=ConfigurationTool::DEFAULT_PAGE_SIZE)
    {
        return Community::select('school_id', 'name', 'detail', 'logo', 'pic1', 'pic2', 'pic3', 'user_id')
            ->where('school_id', $schoolId)
            ->where('status', 1)
            ->orderBy('id','DESC')
            ->simplePaginate($pageSize);
    }
    public function getCommunity($schoolId,$communityId,$isShow= true)
    {
        $map = ['school_id'=>$schoolId, 'id'=>$communityId];
        if($isShow) {
            $map['school_id'] = Community::STATUS_CHECK;
        }
        return Community::where($map)->first();
    }

    public function getCommunityMembers($schoolId,$communityId)
    {
        return Community_member::where('school_id', $schoolId)
            ->where('community_id', $communityId)
            ->get();
    }

    public function joinCommunity($data)
    {
        $re =  Community_member::create($data);
        if($re){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'添加成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'添加失败');
        }
    }

    public function acceptCommunity($schoolId,$communityId,$userId,$memberId)
    {
        if ($this->haveCommunity($schoolId,$communityId,$userId)) {
            return Community_member::where('community_id', $communityId)
                ->where('user_id', $memberId)
                ->update(['status'=>Community_member::ACCEPT]);
        } else {
            return false;
        }
    }
    public function rejectCommunity($schoolId,$communityId,$userId,$memberId)
    {
        if ($this->haveCommunity($schoolId,$communityId,$userId)) {
            return Community_member::where('community_id', $communityId)
                ->where('user_id', $memberId)
                ->update(['status'=>Community_member::REJECT]);
        } else {
            return false;
        }
    }

    public function haveCommunity($schoolId,$communityId,$userId)
    {
        $communities = Community::where('school_id', $schoolId)->where('user_id',$userId)->get();
        foreach ($communities as $community) {
            if ($community->id == $communityId)
                return true;
        }
        return false;
    }


    /**
     * @param $schoolId
     * @return mixed
     */
    public function getCommunityBySchoolId($schoolId) {
        return Community::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 编辑
     * @param $id
     * @param $data
     * @return MessageBag
     */
    public function updateCommunityById($id, $data) {

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        try{
            DB::beginTransaction();
            // forum_type_id为空的，创建类型
            if(is_null($data['forum_type_id']) && $data['status'] == Community::STATUS_CHECK) {
                $type = [
                    'school_id' => $data['school_id'],
                    'title'     => $data['name'],
                    'type'      => ForumType::TYPE_TEAM
                ];
                $re = ForumType::create($type);
                $data['forum_type_id'] = $re->id;
            }
            Community::where('id',$id)->update($data);
            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $messageBag->setMessage($msg);
        }
        return $messageBag;

    }


    /**
     * 删除社团
     * @param Community
     * @return MessageBag
     */
    public function deleteCommunity($community){

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        try{
            DB::beginTransaction();
            // 删除社团表
            Community::where('id', $community->id)->delete();
            // 删除社团成员表
            Community_member::where('community_id', $community->id)->delete();
            // 删除社团的分类
            ForumType::where('id', $community->forum_type_id)->delete();
            // todo 删除图片资源
            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setMessage($msg);
        }

        return $messageBag;
    }

}
