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

use App\Models\Affiche\CollegeGroupNotice;
use Illuminate\Support\Facades\DB;

class CollegeGroupNoticesDao extends \App\Dao\Affiche\CommonDao
{
    /**
     * Func 添加群信息
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addCollegeGroupNoticesInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = CollegeGroupNotice::create($data)) {
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
     * Func 更新数据
     *
     * @param $id 更新Id
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function editCollegeGroupNoticesInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (CollegeGroupNotice::where('noticeid',$id)->update($data)) {
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
     * Func 公告列表
     *
     * @param['group_id']  组id
     * @param['page']  分页ID
     * @param['limit']  每页获取条数
     *
     * @return array
     */
    public function getCollegeGroupNoticesListInfo($group_id = 0 , $page = 1 , $limit = 10)
    {
        if (!intval($group_id)) {
            return [];
        }

        // 检索条件
        $condition[] = ['group_id', '=', (Int)$group_id];
        $condition[] = ['status', '=', 1];

        // 获取的字段
        $fieldArr = ['noticeid', 'user_id', 'notice_content', 'notice_number2 as notice_number1', 'group_id', 'created_at'];

        $data = CollegeGroupNotice::where($condition)
            ->select($fieldArr)
            ->orderBy('noticeid', 'desc')
            ->offset($this->offset($page))
            ->limit($limit ? $limit : self::$limit)
            ->get();

        return !empty($data) ? $data->toArray() : [];
    }

    /**
     * Func 公告详情
     *
     * @param['noticeid'] 公告id
     *
     * @return array
     */
    public function getCollegeGroupNoticesOneInfo($noticeid = 0)
    {
        if (!intval($noticeid)) {
            return [];
        }

        // 检索条件
        $condition[] = ['noticeid', '=', (Int)$noticeid];
        $condition[] = ['status', '=', 1];

        $data = CollegeGroupNotice::where($condition)->first(['*']);

        $data = !empty($data) ? $data->toArray() : [];

        return $data;
    }
}
