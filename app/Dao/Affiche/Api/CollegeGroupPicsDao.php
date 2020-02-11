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

use App\Models\Affiche\CollegeGroupPics;
use Illuminate\Support\Facades\DB;

class CollegeGroupPicsDao extends \App\Dao\Affiche\CommonDao
{
    /**
     * Func 添加
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addCollegeGroupPicsInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = CollegeGroupPics::create($data)) {
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
     * Func 获取群组设置的图片
     *
     * @param['group_id']  组id
     * @param['page']  分页ID
     *
     * @return array
     */
    public function getCollegeGroupPicsListInfo($group_id = 0, $page = 1)
    {
        if (!intval($group_id)) {
            return [];
        }

        // 检索条件
        $condition[] = ['group_id', '=', (Int)$group_id];
        $condition[] = ['status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'picsid', 'pics_smallurl', 'pics_bigurl'
        ];

        $data = CollegeGroupPics::where($condition)->select($fieldArr)
            ->orderBy('picsid', 'desc')
            ->offset($this->offset($page))
            ->limit(self::$limit)
            ->get();

        return !empty($data) ? $data->toArray() : [];
    }

}
