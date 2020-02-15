<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Welcome\Backstage;

use App\Models\Welcome\WelcomeConfig;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class WelcomeConfigDao extends \App\Dao\Welcome\CommonDao
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
    public function addWelcomeConfigInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = WelcomeConfig::create($data)) {
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
    public function editWelcomeConfigInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (WelcomeConfig::where('configid',$id)->update($data)) {
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
     * Func 获取学校是否配置迎新
     *
     * @param['school_id']  学校id
     *
     * @return array
     */
    public function getWelcomeConfigOneInfo($school_id = 0)
    {
        if (!intval($school_id)) return [];

        // 检索条件
        $condition[] = ['school_id', '=', $school_id];

        $data = WelcomeConfig::where($condition)->first(['*']);

        return !empty($data) ? $data->toArray() : [];
    }

}
