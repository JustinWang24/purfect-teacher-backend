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

use App\Models\Affiche\CollegeGroupNotice;
use Illuminate\Support\Facades\DB;

class CollegeGroupNoticesDao extends \App\Dao\Affiche\CommonDao
{
    /**
     * Func 获取公告列表
     *
     * @param['group_id'] 是 int 组织id
     * @param['keywords'] 否 string 关键词
     * @param['status'] 是 array 状态(数组)
     * @param['page']  int 分页ID
     *
     * @return array
     */
    public function getCollegeGroupNoticesListInfo($param = [], $page = 1)
    {
        $condition = [];
        // 检索条件
        if (isset($param['group_id']) && $param['group_id'] > 0) {
            $condition[] = ['group_id', '=', (Int)$param['group_id']];
        }
        // 获取的字段
        $fieldArr = ['*'];

        return CollegeGroupNotice::where($condition)
            ->whereIn('status',(array)$param['status'] )
            ->where('notice_content', 'like', '%'.trim($param['keywords']).'%')
            ->orderBy('noticeid', 'desc')
            ->select($fieldArr)
            ->paginate(self::$bcckend_limit, $fieldArr, 'page', $page);
    }

}
