<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Backstage;

use App\Models\Affiche\Affiche;
use App\Models\Affiche\AffichePics;
use App\Models\Affiche\AfficheVideo;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class AfficheDao extends \App\Dao\Affiche\CommonDao
{
    public function __construct(){
    }

    /**
     * Func 添加
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addAffichesInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = Affiche::create($data)) {
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
    public function editAffichesInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (Affiche::where('icheid',$id)->update($data)) {
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
     * Func 获取动态列表
     *
     * @param['school_id'] 否 int 学校Iid
     * @param['keywords'] 是 string 关键词
     * @param['status'] 是 array 状态(数组)
     * @param['page']  int 分页ID
     *
     * @return array
     */
    public function getAfficheListInfo($param = [], $page = 1)
    {
        $condition = [];
        // 检索条件
        if (isset($param['school_id']) && $param['school_id'] > 0) {
            $condition[] = ['a.school_id', '=', (Int)$param['school_id']];
        }
        if (isset($param['campus_id']) && $param['campus_id'] > 0) {
            $condition[] = ['a.campus_id', '=', (Int)$param['campus_id']];
        }

        // 获取的字段
        $fieldArr = ['a.*', 'b.name', 'b.mobile'];

        return Affiche::from('affiches as a')
            ->where($condition)
            ->whereIn('a.status',(array)$param['status'] )
            ->where('b.mobile', 'like', '%'.trim($param['keywords']).'%')
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->orderBy('a.icheid', 'desc')
            ->select($fieldArr)
            ->paginate(self::$bcckend_limit, $fieldArr, 'page', $page);
    }

    /**
     * Func 获取动态详情
     *
     * @param['icheid']  动态id
     *
     * @return array
     */
    public function getAfficheOneInfo($icheid = 0)
    {
        if (!intval($icheid)) return [];

        // 获取的字段
        $fieldArr = ['*'];

        return Affiche::where('icheid', '=', $icheid)->first($fieldArr);
    }







    /**
     * Func 获取用户额动态列表
     *
     * @param['user_id']  用户id
     * @param['page']  分页ID
     *
     * @return array
     */
    public function getMyAfficheListInfo($user_id = 0, $page = 1)
    {
        // 检索条件
        $condition[] = ['user_id', '=', (Int)$user_id]; // 用户id
        $condition[] = ['status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'icheid', 'user_id', 'school_id' , 'iche_type', 'iche_title',
            'iche_content', 'iche_view_num', 'iche_share_num',
            'iche_praise_num', 'iche_comment_num', 'created_at'
        ];

        $data = Affiche::where($condition)->select($fieldArr)
            ->orderBy('icheid', 'desc')
            ->offset($this->offset($page))
            ->limit(self::$limit)
            ->get();

        return !empty($data) ? $data->toArray() : [];
    }


    /**
     * Func 获取动态图片列表
     *
     * @param['iche_id']  动态ID
     *
     * @return array
     */
    public function getAffichePicsListInfo($iche_id = 0, $limit = 10 )
    {
        if (!intval($iche_id)) return [];

        // 查询条件
        $condition[] = ['iche_id', '=', $iche_id];
        $condition[] = ['status', '=', 1];

        $data = AffichePics::where($condition)
                ->select(['picsid', 'pics_smallurl', 'pics_bigurl'])
                ->orderBy('picsid', 'desc')
                ->get();

        return !empty($data->toArray()) ? $data->toArray() : [];
    }

    /**
     * Func 获取动态视频列表
     *
     * @param['iche_id']  动态ID
     *
     * @return array
     */
    public function getAfficheVideoListInfo($iche_id = 0, $limit = 10 )
    {
        if (!intval($iche_id)) return [];

        // 查询条件
        $condition[] = ['iche_id', '=', $iche_id];
        $condition[] = ['status', '=', 1];

        $data = AfficheVideo::where($condition)
            ->select(['video_url', 'cover_url', 'video_url'])
            ->orderBy('videoid', 'desc')
            ->get();

        return  !empty($data->toArray()) ? $data->toArray() : [];
    }


    /**
     * Func 获取动态视频详情
     *
     * @param['iche_id']  动态ID
     *
     * @return array
     */
    public function getAfficheVideoOneInfo($iche_id = 0)
    {
        if (!intval($iche_id)) return [];

        // 查询条件
        $condition[] = ['iche_id', '=', $iche_id];
        $condition[] = ['status', '=', 1];

        $fileArr = ['video_url', 'cover_url', 'video_url'];
        $data = AfficheVideo::where($condition)->first($fileArr);

        return !empty($data) ? $data->toArray() : [];
    }

    /**
     * Func 获取动态视频详情
     *
     * @param['iche_id']  动态ID
     *
     * @return array
     */
    public function getAfficheVideoOneInfo1($iche_id = 0)
    {
        if (!intval($iche_id)) return [];

        // 查询条件
        $condition[] = ['iche_id', '=', $iche_id];
        $condition[] = ['status', '=', 1];

        $fileArr = ['video_url', 'cover_url', 'video_url'];
        $data = AfficheVideo::where($condition)->first($fileArr);

        return !empty($data) ? $data->toArray() : (object)null;
    }
}
