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

use App\Models\Affiche\CollegeGroupNoticeReader;

use Illuminate\Support\Facades\DB;

class CollegeGroupNoticeReaderDao extends \App\Dao\Affiche\CommonDao
{
    /**
     * Func 添加群信息
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addCollegeGroupNoticeReaderInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = CollegeGroupNoticeReader::create($data)) {
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
    public function editCollegeGroupNoticeReaderInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (CollegeGroupNoticeReader::where('readerid',$id)->update($data)) {
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
     * Func 获取公告信息
     *
     * @param['user_id']  是  用户id
     * @param['noticeid']  是  公告id
     *
     * @return array
     */
    public function getCollegeGroupNoticeReaderOneInfo($user_id = 0, $noticeid = 0)
    {
        if (!intval($user_id) || !intval($noticeid)) {
            return [];
        }

        // 检索条件
        $condition[] = ['user_id', '=', (Int)$user_id];
        $condition[] = ['notice_id', '=', (Int)$noticeid];

        $data = CollegeGroupNoticeReader::where($condition)->orderBy('readerid','desc')->first(['*']);

        $data = !empty($data) ? $data->toArray() : [];

        return $data;
    }


    /**
     * Func:  统计数据
     *
     * @Param $condition array 查询条件
     * return Int
     */
    public static function getCollegeGroupNoticeReaderCount($condition = [])
    {
        // 条件/ 排序必须唯一,必传参数
        if (!$condition) return 0;
        return (Int)CollegeGroupNoticeReader::where($condition)->count();
    }


}
