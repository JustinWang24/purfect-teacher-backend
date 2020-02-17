<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Welcome\Backstage;

use App\Models\Welcome\WelcomeUserReport;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class WelcomeUserReportDao extends \App\Dao\Welcome\CommonDao
{
    public function __construct()
    {
    }

    /**
     * Func 待报到列表
     *
     * @param['school_id']  学校id
     * @param['page']  分页id
     *
     * @return array
     */
    public function getWelcomeUserReportListInfo($school_id = 0, $status = 0, $page = 1)
    {
        if (!intval($school_id) || !intval($status) || !intval($page)) {
            return [];
        }

        // 检索条件
        $condition[] = ['school_id', '=', (Int)$school_id];
        $condition[] = ['status', '=', $status];

        return WelcomeUserReport::where($condition)
            ->orderBy('configid', 'desc')
            ->offset($this->offset($page))
            ->paginate(self::$limit, ['*'], 'page', $page);
    }

    /**
     * Func 详情
     *
     * @param['uuid']  uuid
     *
     * @return array
     */
    public function getWelcomeUserReportOneInfo($uuid = '')
    {
        if (!trim($uuid)) {
            return [];
        }
        $data = WelcomeUserReport::where('uuid', '=', $uuid)->first();
        return !empty($data) ? $data->toArray() : [];
    }
}
