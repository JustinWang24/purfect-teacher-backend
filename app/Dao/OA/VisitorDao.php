<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/11/19
 * Time: 4:33 PM
 */

namespace App\Dao\OA;

use App\Dao\Affiche\CommonDao;
use App\Models\OA\Visitor;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
class VisitorDao
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
    public function addVisitorInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = Visitor::create($data)) {
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
    public function editVisitorInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (Visitor::where('id',$id)->update($data)) {
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
     * 根据学校获取访客列表
     * @param $schoolId
     * @return Collection
     */
    public function getVisitorsBySchoolId($schoolId){
        return Visitor::where('school_id',$schoolId)
            ->orderBy('id','desc')->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    /**
     * Func 获取今天访客数据
     * @param $schoolId
     * @param $sdate
     * @param $edata
     * @param $status
     * @return mixed
     */
    public function getTodayVisitorsBySchoolId($schoolId,$sdate,$edata)
    {
        return Visitor::where('school_id', $schoolId)
            ->whereBetween('created_at', [$sdate, $edata])
            ->orderBy('id', 'desc')->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * Func 统计今天的数量
     * @param $schoolId
     * @param $sdate
     * @param $edata
     * @param $status
     * @return mixed
     */
    public function getTodayVisitorsBySchoolIdCount($schoolId, $sdate, $edata, $status)
    {
        return Visitor::where('school_id', $schoolId)
            ->whereBetween('created_at', [$sdate, $edata])
            ->where('status', '=', $status)
            ->count();
    }


    /**
     * @param $schoolId
     * @param $date
     * @return Collection
     */
    public function getTodayVisitorsBySchoolIdForApp($schoolId, $date){
        return Visitor::where('school_id',$schoolId)
            ->whereDate('scheduled_at',$date)
            ->orderBy('id','desc')->get();
    }

    /**
     * Func Api 获取到访列表
     *
     * @param['user_id']  用户id
     * @param['typeid']  类型(1：今日来访,2：已邀请:3：已过期)
     * @param['page']  分页ID
     *
     * @return array
     */
    public function getVisitorListInfo($user_id = 0, $typeid = 1, $page = 1)
    {
        // 检索条件
        $condition[] = ['user_id', '=', $user_id];
        $condition[] = ['status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'id', 'user_id', 'cate_id', 'name', 'mobile','visitors_json1',
            'created_at', 'scheduled_at', 'arrived_at','status'
        ];


        $data = Visitor::where($condition)->select($fieldArr)
            ->orderBy('id', 'desc')
            ->offset((new CommonDao)->offset($page))
            ->limit(CommonDao::$limit)
            ->get();

        return !empty($data) ? $data->toArray() : [];
    }


    /**
     * Func Api 获取到访详情
     *
     * @param['id']  到访id
     * @param['user_id']  用户id
     *
     * @return array
     */
    public function getVisitorOneInfo($id = 0 ,$user_id = 0)
    {
        // 检索条件
        $condition[] = ['id', '=', $id];
        $condition[] = ['user_id', '=', $user_id];
        $condition[] = ['status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'id', 'user_id', 'cate_id', 'name', 'mobile', 'vehicle_license',
            'reason', 'visitors_json1', 'visitors_json2',
            'scheduled_at', 'arrived_at', 'created_at'
        ];

        $data = Visitor::where($condition)->first($fieldArr);

        return !empty($data) ? $data->toArray() : [];

    }

    /**
     * Func Api 获取到访详情
     *
     * @param['id']  到访id
     * @param['user_id']  用户id
     *
     * @return array
     */
    public function getUuidVisitorOneInfo($uuid)
    {
        // 检索条件
        $condition[] = ['uuid', '=', $uuid];

        // 获取的字段
        $fieldArr = [
            'id', 'user_id', 'cate_id', 'name', 'mobile', 'vehicle_license',
            'qrcode_url', 'reason', 'visitors_json1', 'visitors_json2',
            'scheduled_at', 'arrived_at', 'created_at', 'status'
        ];

        $data = Visitor::where($condition)->first($fieldArr);

        return !empty($data) ? $data->toArray() : [];

    }

    /**
     * Func Api 获取分享信息
     *
     * @return array
     */
    public function getShareInfo($user_id = 0)
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['share_url'] = 'http://www.baidu.com/'.$data['uuid'];
        return $data;
    }

    /**
     * Func Api 获取到访状态
     *
     * @param['$visitorInfo']  到访信息
     *
     * @return array
     */
    public function getVisitorStatusInfo($visitorInfo = array())
    {
        // TODO.....
        return array('status' => 0, 'status_str' => '未到访');
    }
}
