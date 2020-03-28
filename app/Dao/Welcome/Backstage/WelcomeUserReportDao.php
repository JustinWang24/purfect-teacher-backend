<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Welcome\Backstage;

use App\Models\Welcome\WelcomeUserReport;
use App\Models\Welcome\WelcomeUserReportsProject;

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
     * Func 修改
     *
     * @param $id 更新Id
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function editWelcomeUserReportsInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (WelcomeUserReport::where('configid',$id)->update($data)) {
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
     * Func 待报到列表
     *
     * @param['school_id']  学校id
     * @param['page']  分页id
     *
     * @return array
     */
    public function getWelcomeUserReportListInfo($school_id = 0, $status = [], $keywords = null , $page = 1)
    {
        if (!intval($school_id) || !intval($status) || !intval($page)) {
            return [];
        }

        // 检索条件
        $condition[] = ['school_id', '=', (Int)$school_id];
        if ($keywords) {
            $condition[] = ['user_name', 'like', '%'.trim($keywords).'%'];
        }

        return WelcomeUserReport::where($condition)
            ->whereIn('status', $status)
            ->orderBy('configid', 'desc')
            ->offset($this->offset($page))
            ->paginate(self::$limit, ['*'], 'page', $page);
    }

    /**
     * Func 待报到列表
     *
     * @param['school_id']  是 Int 学校id
     * @param['status']  是 Int 报道中状态(1,3)
     * @param['keywords']  否 String 姓名
     * @param['typeid']  是 Int 缴费类型id
     * @param['isfee'] 否 Int 显示(1:未交费,2:已缴费)
     * @param['page']  否 Int 分页id
     *
     * @return array
     */
    public function getUserReportsOrProjectsListInfo($school_id = 0, $status = [], $keywords = '' , $typeid = 0, $isfee = 1, $page = 1)
    {
        if (!intval($school_id) || empty($status) || !intval($typeid) || !intval($page)) {
            return [];
        }

        // 检索条件
        $condition[] = ['a.school_id', '=', (Int)$school_id];
        if ($keywords) {
            $condition[] = ['a.user_name', 'like', '%' . trim($keywords) . '%'];
        }

        // 获取的字段
        $fieldArr = ['a.*',DB::raw("( select count(*) from welcome_user_reports_projects as b where a.user_id = b.user_id and b.typeid ='{$typeid}') count")];

        return WelcomeUserReport::from('welcome_user_reports as a')
            ->where($condition)
            ->whereIn('a.status', $status)
            ->having('count', $isfee == 1 ? '=' : '>', 0)
            ->offset($this->offset($page))
            ->simplePaginate(self::$limit, $fieldArr, 'page', $page);
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

    /**
     * Func 获取缴费详情
     *
     * @param['user_id']  用户id
     * @param['typeid']  类型(1:学费,2:书费,3:住宿费,4:生活用品,5:军训服装,10:报到函,11:党团文件,12:录取通知书)
     *
     * @return array
     */
    public function getWelcomeUserReportsProjectsListInfo($user_id = 0, $typeid = [])
    {
        if (!intval($user_id) || empty($typeid)) {
            return [];
        }

        $condition[] = ['user_id', '=', (Int)$user_id];
        $condition[] = ['status', '=', 1];

        $data = WelcomeUserReportsProject::where($condition)
            ->whereIn('typeid', $typeid)
            ->orderBy('projectid', 'asc')->get();

        return !empty($data) ? $data->toArray() : [];
    }

    /**
     * Func 获取缴费信息
     *
     * @param['user_id']  用户id
     * @param['typeid']  类型(1:学费,2:书费,3:住宿费,4:生活用品,5:军训服装)
     *
     * @return array
     */
    public function getWelcomeUserReportsProjectsInfo($user_id = 0, $typeid = 0)
    {
        if (!intval($user_id) && !intval($typeid)) {
            return [];
        }

        $condition[] = ['user_id', '=', (Int)$user_id];
        $condition[] = ['typeid', '=', (Int)$typeid];
        $condition[] = ['status', '=', 1];

        $data = WelcomeUserReportsProject::where($condition)->orderBy('projectid','desc')->first();

        return !empty($data) ? $data->toArray() : [];
    }

    /**
     * Func 添加报到缴费信息
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addWelcomeUserReportsProjectInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = WelcomeUserReportsProject::create($data)) {
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
     * Func 用户缴费项完成后更新报到完成
     *
     * @param $user_id 用户id
     * @param $typeArr 类型数组
     *
     * @return false|id
     */
    public function updateUserReportCompleteInfo($user_id, $typeArr = [])
    {
        if (!$user_id || empty($typeidArr)) return;

        $typeInfo = WelcomeUserReportsProject::where('user_id', '=', $user_id)->get();
        $typeIdArr = !empty($data) ? $data->toArray() : [];
        if (empty($typeIdArr)) return;
        $typeIdArr = array_unique(array_filter(array_column($typeIdArr, 'typeid')));

        $intersection = array_diff($typeArr,$typeIdArr);

        if(empty($intersection))
        {
             WelcomeUserReportsProject::where('user_id','=',$user_id)->update(
                 ['complete_date'=>date('Y-m-d H:i:s'),'status'=>3]
             );
        }
        return;
    }

}
