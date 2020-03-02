<?php

namespace App\Http\Controllers\Api\Version;

use App\Dao\Users\UserDao;
use App\Dao\Version\VersionDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class VersionController extends Controller
{
    /**
     * Func 获取版本更新
     *
     * @param Request $request
     * @param['token'] 是  token
     *
     * @return Json
     */
    public function index(Request $request)
    {
        $token = (String)$request->input('token', '');
        $typeid = (Int)$request->input('typeid', 0);
        $version_id = (Int)$request->input('version_id', 0);
        $user_apptype = (Int)$request->input('user_apptype', 0);

        $typeid = in_array($typeid, [1, 2]) ? $typeid : 1;

        // 验证登录
        $user_id = $school_id = 0;
        if ($token != '') {
            $userInfo = DB::table('users')
                ->where('api_token','=',$token)
                ->orderBy('id')->first();
            $user_id = $userInfo->id;
            //$school_id = $user->gradeUserOneInfo->school_id;
            $school_id = 1;
        }

        // 获取数据
        $condition[] = ['user_apptype', '=', (Int)$user_apptype];
        $condition[] = ['typeid', '=', (Int)$typeid];
        $condition[] = ['status', '=', 1];
        if ($school_id) {
            $infos = DB::table('versions')
                ->where($condition)
                ->whereRaw("FIND_IN_SET($school_id,schoolids)")
                ->orderBy('version_id', 'desc')
                ->first();
        } else {
            $infos = DB::table('versions')
                ->where($condition)
                ->orderBy('version_id', 'desc')
                ->first();
        }

        if (isset($infos->sid))
        {
           $infos->version_isshow = 0; // 不显示
            if ( $version_id < $infos->version_id )
            {
                if ( $infos->isupdate == 2 )
                {
                    if( time() < $infos->vserion_invalidtime )
                    {
                        $infos->version_isshow = 1;

                        if ( $user_id )
                        {
                            // 每天只需要弹一次
                            $condition1[] = ['userid', '=', (Int)$user_id];
                            $condition1[] = ['typeid', '=', (Int)$typeid];
                            $condition1[] = ['version_id', '=', (Int)$version_id];
                            $condition1[] = ['date', '=', date('Y-m-d')];
                            $condition1[] = ['user_apptype', '=', $user_apptype];
                            if ( DB::table('versions')->where($condition1)->count() )
                            {
                                $infos->version_isshow = 0; // 每天只需要弹一次
                            } else {
                                $infos->version_isshow = 1;
                                // 记录当前弹出的一次记录
                                $addData[ 'create_time' ] = time();
                                $addData[ 'typeid' ]      = $typeid;
                                $addData[ 'userid' ]      = $user_id;
                                $addData[ 'version_id' ]  = $version_id;
                                $addData[ 'date' ]        = date('Y-m-d');
                                $addData[ 'user_apptype' ]= $user_apptype;
                                DB::table('versions')->insert($addData);
                            }
                        }
                    } else {
                        // 已过有效期不需要弹框
                        $infos->version_isshow = 0;
                    }
                } else {
                    $infos->version_isshow = 1;
                }
            }
        }
        return JsonBuilder::Success($infos, '获取版本更新');
    }

}
