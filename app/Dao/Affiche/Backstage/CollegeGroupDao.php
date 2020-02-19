<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Backstage;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;

use App\Models\Affiche\CollegeGroup;
use App\Models\Affiche\CollegeGroupJoin;
use Illuminate\Support\Facades\DB;

class CollegeGroupDao extends \App\Dao\Affiche\CommonDao
{

    /**
     * Func 添加群信息
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addCollegegroupInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = CollegeGroup::create($data)) {
                DB::commit();
                return $obj->id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Func 更新动态字段信息
     *
     * @param $id 更新Id
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function editCollegegroupOneInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (CollegeGroup::where('groupid',$id)->update($data)) {
                DB::commit();
                return $id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }


    /**
     * Func 获取动态列表
     *
     * @param['school_id'] 否 int 学校Iid
     * @param['keywords'] 是 string 关键词
     * @param['status'] 是 array 状态(数组)
     * @param['page']  int 分页ID
     *
     * @return array
     */
    public function getCollegeGroupListInfo($param = [], $page = 1)
    {
        $condition = [];
        // 检索条件
        if (isset($param['group_typeid']) && $param['group_typeid'] > 0) {
            $condition[] = ['a.group_typeid', '=', (Int)$param['group_typeid']];
        }
        if (isset($param['school_id']) && $param['school_id'] > 0) {
            $condition[] = ['a.school_id', '=', (Int)$param['school_id']];
        }
        // 获取的字段
        $fieldArr = ['a.*', 'b.name', 'b.mobile', 'b.nice_name'];

        return CollegeGroup::from('college_groups as a')
            ->where($condition)
            ->whereIn('a.status',(array)$param['status'] )
            ->where('b.mobile', 'like', '%'.trim($param['keywords']).'%')
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->orderBy('a.groupid', 'desc')
            ->select($fieldArr)
            ->paginate(self::$bcckend_limit, $fieldArr, 'page', $page);
    }

    /**
     * Func 组织详情
     *
     * @param['groupid']  组织id
     *
     * @return array
     */
    public function getCollegeGroupOneInfo($groupid = 0)
    {
        if (!intval($groupid)) {
            return [];
        }
        return CollegeGroup::where('groupid', '=', $groupid)
            ->orderBy('groupid', 'desc')
            ->first();
    }

}
