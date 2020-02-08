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
     * Func 获取单个群组信息
     *
     * @param['group_id']  群组id
     * @param['fieldArr']  获取的字段
     *
     * @return array
     */
    public function getCollegegroupOneInfo($group_id = 0)
    {
        if (!intval($group_id)) {
            return [];
        }

        // 获取的字段
        $fieldArr = [
            'groupid', 'user_id', 'group_typeid', 'group_pics',
            'group_title', 'group_number', 'group_time1', 'status'
        ];

        $data = CollegeGroup::where('groupid', '=', $group_id)
            ->orderBy('groupid', 'desc')
            ->first($fieldArr);

        return !empty($data) ? $data->toArray() : [];
    }

    /**
     * Func 获取我的待审核
     *
     * @param['user_id']  是  用户id
     * @param['group_typeid']  是  类型(1:学生会,2:社团)
     *
     * @return array
     */
    public function getCheckGroupOneInfo($user_id = 0,$group_typeid = 0)
    {
        if (!intval($user_id)) {
            return [];
        }
        // 查询条件
        // 状态(-1:待审核,1:审核通过,2:审核驳回)
        $data = CollegeGroup::where('user_id', '=', (Int)$user_id)
            ->whereIn('status', [-1, 2])
            ->where('group_typeid','=',$group_typeid)
            ->orderBy('groupid', 'desc')
            ->first(['*']);

        $data = !empty($data) ? $data->toArray() : [];

        return $data;
    }

    /**
     * Func 获取我的待审核
     *
     * @param['user_id']  是  用户id
     * @param['group_typeid']  是  类型(1:学生会,2:社团)
     * @param['group_title']  是  标题
     *
     * @return array
     */
    public function getCollegeGroupNameOneInfo($user_id = 0, $group_typeid = 0, $group_title = '')
    {
        if (!intval($user_id) || !intval($group_typeid) || !trim($group_title)) {
            return [];
        }
        $data = CollegeGroup::where('user_id', '=', (Int)$user_id)
            ->where('group_typeid', '=', $group_typeid)
            ->where('group_title', 'like', '%' . trim($group_title) . '%')
            ->where('status', '=', 1)
            ->orderBy('groupid', 'desc')
            ->first(['*']);

        $data = !empty($data) ? $data->toArray() : [];

        return $data;
    }

    /**
     * Func:  统计数据
     *
     * @Param $condition array 查询条件
     * @Param $field string 获取的字段值
     *
     * 实例：[['iche_id', '=', $infos['icheid'], ['status', '=', 1]]]
     *
     * return Int
     */
    public static function getCollegeGroupCount ( $condition = [] , $mode = 'count' , $field = null )
    {
        // 条件/ 排序必须唯一,必传参数
        if ( ! $condition ) return 0;

        if ( in_array ( $mode , [ 'max' , 'min' , 'avg' , 'sum' ] ) && ! $field ) return 0;

        if ( $mode == 'count' ) return CollegeGroup::where ( $condition )->count ();
        if ( $mode == 'max' ) return CollegeGroup::where ( $condition )->max ( $field );
        if ( $mode == 'min' ) return CollegeGroup::where ( $condition )->min ( $field );
        if ( $mode == 'avg' ) return (float)CollegeGroup::where ( $condition )->avg ( $field );
        if ( $mode == 'sum' ) return (float)CollegeGroup::where ( $condition )->sum ( $field );
        return 0;
    }

    /**
     * Func 计算管理员人员
     *
     * @param $group_id 群组id
     *
     * return bool
     */
    public function calculateCollegeGroupInfo ( $group_id = 0 )
    {
        if (!intval($group_id)) return;

        // 状态(-1:待审核,1:审核通过,2:审核驳回)
        $condition[] = ['status', '=', 1];
        $condition[] = ['group_id', '=', $group_id];
        $number = (Int)CollegeGroupJoin::where($condition)->count();

        $this->editCollegegroupOneInfo(['group_number'=>$number], $group_id);

        return;
    }
}
