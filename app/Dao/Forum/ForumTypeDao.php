<?php


namespace App\Dao\Forum;


use App\Models\Forum\ForumType;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Database\Eloquent\Model;

class ForumTypeDao extends Model
{

    /**
     * 论坛类别
     * @param $schoolId
     * @return mixed
     */
    public function typeListBySchoolId($schoolId) {
        return ForumType::where('school_id',$schoolId)->get();
    }

    /**
     * 添加类别
     * @param $data
     * @return ForumType
     */
    public function add($data)
    {
        return ForumType::create($data);
    }

    /**
     * 查询所有分类
     * @return mixed
     */
    public function getAllType()
    {
        return ForumType::orderBy('created_at','desc')->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
}
