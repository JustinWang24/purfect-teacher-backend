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
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Exception\InvalidPathException;

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
     * @param $param['keywords'] 搜索关键词
     * @param $param['status'] 状态
     * @return Collection
     */
    public function getVisitorsBySchoolId($schoolId,$param = []){

        // 判断状态
        $param['status'] = !empty($param['status']) ? [$param['status']] : [2, 3];

        $sdate = !empty($param['startDate']) ? trim($param['startDate']) : date('Y-01-01 00:00:00');
        $edata = !empty($param['endDate']) ? trim($param['endDate']) : date('Y-12-31 23:59:50');

        return Visitor::from('visitors as a')
            ->whereIn('a.status',$param['status'])
            ->where('a.school_id',$schoolId)
            ->whereBetween('a.created_at', [$sdate, $edata])
            ->where([['a.name','like', '%'.trim($param['keywords']).'%'],['b.name','like', '%'.trim($param['keywords']).'%','or']])
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->orderBy('a.id','desc')
            ->select(['a.*','b.name as uname'])
            ->paginate(10);
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
        return Visitor::from('visitors as a')
            ->where('a.school_id', $schoolId)
            ->whereIn('a.status',[2,3])
            ->whereBetween('a.scheduled_at', [$sdate, $edata])
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->orderBy('a.scheduled_at', 'asc')
            ->select(['a.*','b.name as uname'])
            ->get();
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
            ->whereBetween('scheduled_at', [$sdate, $edata])
            ->whereIn('status',$status)
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

        // 今日开始和结束时间
        $sdate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $edate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1);

        // 今日来访
        if ($typeid == 1) {
            // 开始和结束时间
            $data = Visitor::where('user_id', $user_id)
                ->whereIn('status', [2, 3])
                ->whereBetween('scheduled_at', [$sdate, $edate])
                ->orderBy('scheduled_at', 'asc')
                ->offset((new CommonDao)->offset($page))
                ->limit(CommonDao::$limit)
                ->select($fieldArr)
                ->get();
        }

        // 已邀请
        if($typeid == 2)
        {
            $data = Visitor::where('user_id', $user_id)
                ->whereIn('status', [1, 2])
                ->orderBy('id', 'desc')
                ->offset((new CommonDao)->offset($page))
                ->limit(CommonDao::$limit)
                ->select($fieldArr)
                ->get();
        }

        // 已过期
        if($typeid == 3)
        {
            $data = Visitor::where('user_id', $user_id)
                ->whereIn('status', [2,3])
                ->where('scheduled_at','<',$sdate)
                ->orderBy('scheduled_at', 'desc')
                ->offset((new CommonDao)->offset($page))
                ->limit(CommonDao::$limit)
                ->select($fieldArr)
                ->get();
        }

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
        //$condition[] = ['status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'id', 'user_id', 'cate_id', 'name', 'mobile', 'vehicle_license',
            'reason', 'visitors_json1', 'visitors_json2','status',
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
            'device','qrcode_url', 'reason', 'visitors_json1', 'visitors_json2',
            'scheduled_at', 'arrived_at', 'created_at', 'status'
        ];

        $data = Visitor::where($condition)->first($fieldArr);

        return !empty($data) ? $data->toArray() : [];

    }

    /**
     * Func Api 获取分享地址和二维码地址
     *
     * @return array
     */
    public function getShareInfo($user_id = 0)
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['share_url'] = asset('/special/visitor/index.html?uuid='.$data['uuid']);
        $data['qrcode_url'] = $this->getQrcodeInfo($data['uuid']); // 二维码
        return $data;
    }

    /**
     * Func 生成二维码
     */
    public function getQrcodeInfo($str = '')
    {
        if (!$str) return '';
        $qrCode = new QrCode($str);
        $qrCode->setSize(200);
        $qrCode->setLogoPath(public_path('assets/img/logo.png'));
        $qrCode->setLogoSize(30, 30);
        $qrcode_url = '/assets/img/fangke/' . $str . '.png';
        file_put_contents('.' . $qrcode_url, $qrCode->writeString());
        return asset($qrcode_url);;
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
        if ($visitorInfo['status'] == 1) {
            return array('status' => 1, 'status_str' => '已分享');
        }
        if ($visitorInfo['status'] == 2) {
            return array('status' => 2, 'status_str' => '未到访');
        }
        if ($visitorInfo['status'] == 3) {
            return array('status' => 3, 'status_str' => '已到访');
        }
    }
}
