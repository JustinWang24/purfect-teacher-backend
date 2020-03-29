<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Api;

use App\Models\Affiche\Affiche;
use App\Models\Affiche\AfficheView;
use App\Models\Affiche\AffichePics;
use App\Models\Affiche\AfficheVideo;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class AfficheDao extends \App\Dao\Affiche\CommonDao
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
     * @param['school_id']  学校ID,0表示全部
     * @param['iche_categroypid']  顶级分类ID,0表示全部
     * @param['page']  分页ID
     *
     * @return array
     */
    public function getAfficheListInfo($school_id = 0, $iche_categroypid = 0, $page = 1)
    {
        // 检索条件
        if ($school_id) $condition[] = ['school_id', '=', (Int)$school_id]; // 0 表示全校
        if ($iche_categroypid) $condition[] = ['iche_categroypid', '=', (Int)$iche_categroypid];
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
     * Func 获取动态详情
     *
     * @param['icheid']  动态id
     *
     * @return array
     */
    public function getAfficheOneInfo($icheid = 0)
    {
        if (!intval($icheid)) return [];

        // 检索条件
        $condition[] = ['icheid', '=', $icheid];
        $condition[] = ['status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'icheid', 'user_id', 'school_id' , 'iche_type', 'iche_title',
            'iche_content', 'iche_view_num', 'iche_share_num',
            'iche_praise_num', 'iche_comment_num', 'created_at'
        ];

        $data = Affiche::where($condition)->first($fieldArr);

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
                ->orderBy('picsid', 'asc')
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
            ->orderBy('videoid', 'asc')
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

    /**
     * Func 冬天删除
     *
     * @param['iche_id']  动态ID
     *
     * @return array
     */
    public function delAfficheOneInfo($iche_id = 0)
    {
        if (!intval($iche_id)) return [];

        // 查询条件
        $condition[] = ['icheid', '=', $iche_id];

        return Affiche::where($condition)->update(['status'=>0]);
    }

    /**
     * Func 增加动态浏览数
     *
     * @param $user_id 用户id
     * @param $iche_id 动态id
     *
     * @return
     */
    public function addAfficheViewsInfo($user_id = 0, $iche_id = 0)
    {
        if (!intval($user_id) || !intval($iche_id)) {
            return;
        }

        // 查询条件
        $condition[] = ['user_id', '=', $user_id];
        $condition[] = ['iche_id', '=', $iche_id];
        if (AfficheView::where($condition)->count() <= 0) {
            // 添加数据
            $addData['user_id'] = $user_id;
            $addData['iche_id'] = $iche_id;
            if (AfficheView::create($addData)) {
                // 查询条件
                $count = AfficheView::where('iche_id', '=', $iche_id)->count();
                $this->editAffichesInfo(['iche_view_num' => $count], $iche_id);
            }
        }
        return;
    }
}
