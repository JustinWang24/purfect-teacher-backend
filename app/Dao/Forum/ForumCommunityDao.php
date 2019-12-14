<?php


namespace App\Dao\Forum;


use App\Models\Forum\Community;
use App\Utils\JsonBuilder;
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
}
