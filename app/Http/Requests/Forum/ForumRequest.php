<?php


namespace App\Http\Requests\Forum;

use App\Http\Requests\MyStandardRequest;

class ForumRequest extends MyStandardRequest
{

    public function getForumData(){
        return $this->get('forum');
    }


    public function getCommunitiesData() {
        return $this->get('communities');
    }
}
