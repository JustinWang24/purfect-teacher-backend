<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Api;

use App\Models\Affiche\AuthTissuePics;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class AuthTissuePicDao extends \App\Dao\Affiche\CommonDao
{
    public function __construct()
    {
    }

    /**
     * Func 添加
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addAuthTissuePicInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = AuthTissuePics::create($data)) {
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
     * Func 修改
     *
     * @param $id 更新Id
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function editAuthTissuePicInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (AuthTissuePics::where('icheid',$id)->update($data)) {
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
     * Func 获取动态视频列表
     *
     * @param['iche_id']  动态ID
     *
     * @return array
     */
    public function getAuthTissuePicsListInfo($tissue_id = 0, $limit = 10 )
    {
        if (!intval($tissue_id)) return [];

        // 查询条件
        $condition[] = ['tissue_id', '=', $tissue_id];
        $condition[] = ['status', '=', 1];

        $data = AuthTissuePics::where($condition)
            ->select(['authuid', 'user_id', 'tissue_id', 'pics_small', 'pics_big'])
            ->orderBy('authuid', 'desc')
            ->get();

        return  !empty($data->toArray()) ? $data->toArray() : [];
    }

}
