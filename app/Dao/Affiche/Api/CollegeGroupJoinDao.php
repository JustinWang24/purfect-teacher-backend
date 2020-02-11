<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Api;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;

use App\Models\Affiche\CollegeGroup;
use App\Models\Affiche\CollegeGroupJoin;
use Illuminate\Support\Facades\DB;

class CollegeGroupJoinDao extends \App\Dao\Affiche\CommonDao
{

    /**
     * Func 添加动态评论信息
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addCollegeGroupJoinInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = CollegeGroupJoin::create($data)) {
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
    public function editCollegeGroupJoinInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (CollegeGroupJoin::where('joinid',$id)->update($data)) {
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
     * Func 通过群组id获取成员列表
     * Desc 已审核通过的
     *
     * @param['user_id']  组id
     * @param['page']  分页ID
     * @param['limit']  每页获取条数
     *
     * @return array
     */
    public function getCollegeGroupJoinGroupListInfo($group_id = 0 , $page = 1 , $limit = 10)
    {
        if (!intval($group_id)) {
            return [];
        }

        // 检索条件
        $condition[] = ['group_id', '=', (Int)$group_id];
        $condition[] = ['status', '=', 1];

        // 获取的字段
        $fieldArr = ['joinid', 'group_id', 'user_id', 'join_typeid', 'join_apply_desc1 as user_nickname'];

        $data = CollegeGroupJoin::where($condition)->select($fieldArr)
            ->orderBy('join_typeid', 'asc')
            ->orderBy('created_at', 'desc')
            ->offset($this->offset($page))
            ->limit($limit ? $limit : self::$limit)
            ->get();

        return !empty($data) ? $data->toArray() : [];
    }

    /**
     * Func 获取我加入单个群组的基础信息
     * Desc 通过群组id和用户id获取加入的信息
     *
     * @param['iche_id']  是  动态id
     * @param['commentid']  是  评论id
     *
     * @return array
     */
    public function getGroupidOrUseridJoinOneInfo($user_id = 0, $group_id = 0)
    {
        if (!intval($user_id) || !intval($group_id)) {
            return [];
        }

        // 检索条件
        $condition[] = ['user_id', '=', (Int)$user_id];
        $condition[] = ['group_id', '=', (Int)$group_id];

        $data = CollegeGroupJoin::where($condition)->orderBy('joinid','desc')->first(['*']);

        $data = !empty($data) ? $data->toArray() : [];

        return $data;
    }


    /**
     * Func 获取我加入单个群组的基础信息
     * Desc 通过群组id和用户id获取加入的信息
     *
     * @param['joinid']  是  申请id
     *
     * @return array
     */
    public function getGroupJoinOneInfo($joinid = 0)
    {
        if (!intval($joinid)) {
            return [];
        }

        $data = CollegeGroupJoin::where('joinid', '=', $joinid)->orderBy('joinid', 'desc')->first(['*']);

        $data = !empty($data) ? $data->toArray() : [];

        return $data;
    }
}
