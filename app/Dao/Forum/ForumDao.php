<?php

namespace App\Dao\Forum;

use App\User;
use App\Models\Forum\Forum;
use App\Models\Forum\ForumImage;
use Illuminate\Support\Facades\DB;
use App\Utils\Misc\ConfigurationTool;

class ForumDao
{

    /**
     * @param $data
     * @param $resources
     * @return bool
     */
    public function add($data, $resources)
    {
        DB::beginTransaction();
        try{
            $dataResult = Forum::create($data);
            foreach ($resources as $key => $val) {
                $val['forum_id'] = $dataResult->id;
                ForumImage::create($val);
            }
            DB::commit();
            $result = true;
        }catch (\Exception $e) {
            DB::rollBack();
            $result = false;
        }

        return $result;
    }


    /**
     * 通过学校ID查询论坛
     * @param $schoolId
     * @return mixed
     */
    public function getForumBySchoolId($schoolId) {
        return Forum::where('school_id', $schoolId)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * @param User $user
     * @return Forum
     */
    public function select($user)
    {
        return Forum::where(['school_id'=> $user->getSchoolId(), 'status' => Forum::STATUS_PASS])
            ->select('id', 'content', 'see_num', 'type_id', 'created_at', 'user_id')
            ->orderBy('is_up', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return Forum::find($id);
    }

}
