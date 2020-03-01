<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Backstage;

use App\Models\Affiche\Affiche;
use App\Models\Affiche\AfficheComment;
use App\Models\Affiche\AffichePics;
use App\Models\Affiche\AffichePraise;
use App\Models\Affiche\AfficheView;
use App\Models\Affiche\AfficheVideo;
use App\Models\Affiche\AfficheStick;

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
     * @param['cate_id'] 否 int 类型(1:普通动态,2:社群动态)
     * @param['minx_id'] 否 int 社群id
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
        if (isset($param['cate_id']) && $param['cate_id'] > 0) {
            $condition[] = ['a.cate_id', '=', (Int)$param['cate_id']];
        }
        if (isset($param['minx_id']) && $param['minx_id'] > 0) {
            $condition[] = ['a.minx_id', '=', (Int)$param['minx_id']];
        }

        // 获取的字段
        $fieldArr = ['a.*', 'b.name', 'b.nice_name', 'b.mobile'];

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
     * Func 获取动态评论列表
     *
     * @param['iche_id'] 是 int 动态id
     * @param['keywords'] 是 string 关键词
     * @param['status'] 是 array 状态(数组)
     * @param['page']  int 分页ID
     *
     * @return array
     */
    public function getAfficheCommentListInfo($param = [], $page = 1)
    {
        $condition = [];
        // 检索条件
        if (isset($param['iche_id']) && $param['iche_id'] > 0) {
            $condition[] = ['a.iche_id', '=', (Int)$param['iche_id']];
        }
        // 获取的字段
        $fieldArr = ['a.*', 'b.name', 'b.nice_name', 'b.mobile'];

        return AfficheComment::from('affiche_comments as a')
            ->where($condition)
            ->whereIn('a.status',(array)$param['status'] )
            ->where('b.mobile', 'like', '%'.trim($param['keywords']).'%')
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->orderBy('a.commentid', 'desc')
            ->select($fieldArr)
            ->paginate(self::$bcckend_limit, $fieldArr, 'page', $page);
    }

    /**
     * Func 获取动态点赞列表
     *
     * @param['iche_id'] 是 int 动态id
     * @param['keywords'] 是 string 关键词
     * @param['status'] 是 array 状态(数组)
     * @param['page']  int 分页ID
     *
     * @return array
     */
    public function getAffichePraiseListInfo($param = [], $page = 1)
    {
        $condition = [];
        // 检索条件
        if (isset($param['minx_id']) && $param['minx_id'] > 0) {
            $condition[] = ['a.minx_id', '=', (Int)$param['minx_id']];
        }
        if (isset($param['typeid']) && $param['typeid'] > 0) {
            $condition[] = ['a.typeid', '=', (Int)$param['typeid']];
        }
        // 获取的字段
        $fieldArr = ['a.*', 'b.name', 'b.nice_name', 'b.mobile'];

        return AffichePraise::from('affiche_praises as a')
            ->where($condition)
            ->where('b.mobile', 'like', '%'.trim($param['keywords']).'%')
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->orderBy('a.praiid', 'desc')
            ->select($fieldArr)
            ->paginate(self::$bcckend_limit, $fieldArr, 'page', $page);
    }


    /**
     * Func 获取动态浏览列表
     *
     * @param['iche_id'] 是 int 动态id
     * @param['keywords'] 是 string 关键词
     * @param['status'] 是 array 状态(数组)
     * @param['page']  int 分页ID
     *
     * @return array
     */
    public function getAfficheViewListInfo($param = [], $page = 1)
    {
        $condition = [];
        // 检索条件
        if (isset($param['iche_id']) && $param['iche_id'] > 0) {
            $condition[] = ['a.iche_id', '=', (Int)$param['iche_id']];
        }

        // 获取的字段
        $fieldArr = ['a.*', 'b.name', 'b.nice_name', 'b.mobile'];

        return AfficheView::from('affiche_views as a')
            ->where($condition)
            ->where('b.mobile', 'like', '%'.trim($param['keywords']).'%')
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->orderBy('a.viewsid', 'desc')
            ->select($fieldArr)
            ->paginate(self::$bcckend_limit, $fieldArr, 'page', $page);
    }

    /**
     * Func 动态置顶
     *
     * @param['iche_id'] 是 int 动态id
     * @param['keywords'] 是 string 关键词
     * @param['status'] 是 array 状态(数组)
     * @param['page']  int 分页ID
     *
     * @return array
     */
    public function getAfficheStickListInfo($param = [], $page = 1)
    {
        $condition[] = ['stickid', '>', 0];
        // 检索条件
        if (isset($param['school_id']) && $param['school_id'] != '') {
            $condition[] = ['school_id', '=', (Int)$param['school_id']];
        }
        // 获取的字段
        $fieldArr = ['*'];
        return AfficheStick::where($condition)
            ->where('stick_title', 'like', '%' . trim($param['keywords']) . '%')
            ->orderBy('stick_order', 'desc')
            ->paginate(self::$bcckend_limit, $fieldArr, 'page', $page);
    }

    /**
     * Func 添加
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addAfficheStickInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = AfficheStick::create($data)) {
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
    public function editAfficheStickInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (AfficheStick::where('stickid',$id)->update($data)) {
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
     * Func 删除置顶
     *
     * @param['stickid']  动态id
     *
     * @return array
     */
    public function deleteAfficheStickInfo($stickid = 0)
    {
        if (!intval($stickid)) return [];

        // 获取的字段
        $fieldArr = ['*'];

        return AfficheStick::where('stickid', '=', $stickid)->delete();
    }

}
