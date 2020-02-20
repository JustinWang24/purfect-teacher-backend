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

class CollegeGroupJoinDao extends \App\Dao\Affiche\CommonDao
{

    /**
     * Func 获取组织用户列表
     *
     * @param['group_id'] 否 int 组织认证id
     * @param['keywords'] 是 string 关键词
     * @param['status'] 是 array 状态(数组)
     * @param['page']  int 分页ID
     *
     * @return array
     */
    public function getCollegeGroupJoinDaoListInfo($param = [], $page = 1)
    {
        $condition = [];
        // 检索条件
        if (isset($param['group_id']) && $param['group_id'] > 0) {
            $condition[] = ['a.group_id', '=', (Int)$param['group_id']];
        }
        // 获取的字段
        $fieldArr = ['a.*', 'b.name', 'b.nice_name', 'b.mobile'];

        return CollegeGroupJoin::from('college_group_joins as a')
            ->where($condition)
            ->whereIn('a.status',(array)$param['status'] )
            ->where('b.mobile', 'like', '%'.trim($param['keywords']).'%')
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->orderBy('a.joinid', 'desc')
            ->select($fieldArr)
            ->paginate(self::$bcckend_limit, $fieldArr, 'page', $page);
    }
}
