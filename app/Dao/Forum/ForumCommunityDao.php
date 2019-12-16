<?php


namespace App\Dao\Forum;


use App\Models\Forum\Community;
use App\Models\Forum\Community_member;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;

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
    public function getCommunity($schoolId,$communityId)
    {
        return Community::select('school_id', 'name', 'detail', 'logo', 'pic1', 'pic2', 'pic3', 'user_id','forum_type_id')
            ->where('school_id', $schoolId)
            ->where('id', $communityId)
            ->where('status', 1)
            ->orderBy('id','DESC')
            ->first();
    }

    public function getCommunityMembers($schoolId,$communityId)
    {
        return Community_member::where('school_id', $schoolId)
            ->where('community_id', $communityId)
            ->get();
    }
    public function getCommunityMembersByStatus($schoolId,$communityId,$status=0)
    {
        return Community_member::where('school_id', $schoolId)
            ->where('community_id', $communityId)
            ->where('status', $status)
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
    public function joinCommunityList($data)
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


}
