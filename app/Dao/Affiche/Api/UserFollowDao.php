<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Api;

use App\Dao\Users\UserDao;
use App\Utils\JsonBuilder;

use App\Models\Affiche\UserFollow;
use App\Models\Affiche\AffichePraise;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Builder\UuidBuilderInterface;

class UserFollowDao extends \App\Dao\Affiche\CommonDao
{
    public function __construct()
    {
    }

    /**
     * Func 获取用户是否关注
     *
     * @param['user_id']  是   用户id
     * @param['touser_id'] 是  关注用户uid
     *
     * @return bool
     */
    public function getUserFollowCount($user_id = 0 , $touser_id = 0)
    {
        if( !intval($user_id) || !intval($touser_id))
        {
            return 0;
        }
        // 检索条件
        $condition[] = ['user_id','=',$user_id];
        $condition[] = ['touser_id','=',$touser_id];
        $count = UserFollow::where($condition)->count();
        if($count > 0){
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Func 添加和取消关注
     *
     * @param['user_id']  是  用户id
     * @param['touser_id'] 是 to 用户id
     *
     * @return bool
     */
    public function addOrUpdateUserFollowInfo($user_id = 0 , $touser_id = 0)
    {
        if ( !intval( $user_id ) || !intval( $touser_id ) )
        {
            return [ 'result' => 0 ];
        }

        // 检索条件
        $condition[] = ['user_id','=',$user_id];
        $condition[] = ['touser_id','=',$touser_id];
        $count = UserFollow::where($condition)->count();
        // 取消关注
        if($count > 0)
        {
            // 取消赞
            if (UserFollow::where($condition)->delete()) {
                $this->operationUpdateFollowUserInfo($user_id, $touser_id);
                return ['result' => 0];
            } else {
                $this->operationUpdateFollowUserInfo($user_id, $touser_id);
                return ['result' => 1];
            }
        } else{
            // 添加关注
            $addData['user_id'] = $user_id;
            $addData['touser_id'] = $touser_id;
            $addData['created_at'] = date('Y-m-d H:i:s');
            if (UserFollow::create($addData)) {
                $this->operationUpdateFollowUserInfo($user_id, $touser_id);
                return ['result' => 1];
            } else {
                $this->operationUpdateFollowUserInfo($user_id, $touser_id);
                return ['result' => 0];
            }
        }
        return [ 'result' => 0 ];
    }

    /**
     * Func 获取我的粉丝
     *
     * @param['user_id']  用户id
     * @param['limit'] 获取条数
     *
     * @return array
     */
    public function getUserFollowListInfo($user_id = 0, $page = 1 , $limit= 0 )
    {
        if (!intval($user_id)) return [];
        $limit = $limit ? $limit : self::$limit;

        // 查询条件
        $condition[] = ['user_id', '=', $user_id];
        $condition[] = ['status', '=', 1];

        $data = UserFollow::where($condition)
            ->select(['llowid', 'user_id', 'touser_id'])
            ->orderBy('llowid', 'desc')
            ->offset($this->offset($page))
            ->limit($limit)
            ->get();

        return  !empty($data->toArray()) ? $data->toArray() : [];
    }

    /**
     * Func 获取粉丝总数
     *
     * @param['user_id']  用户id
     * @param['limit'] 获取条数
     *
     * @return array
     */
    public function operationUpdateFollowUserInfo( $user_id , $touser_id )
    {
        // 更新用户关注的
        if (intval($user_id)) {
            $saveData = [];
            $count = (Int)DB::table('user_follows')->where('user_id', '=', $user_id)->count();
            $saveData['user_focus_number'] = $count;
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            DB::table('users')->where('id', '=', $user_id)->update($saveData);
        }
        // 更新关注的人粉丝用户数
        if (intval($touser_id)) {
            $saveData = [];
            $count = (Int)DB::table('user_follows')->where('touser_id', '=', $touser_id)->count();
            $saveData['user_fans_number'] = $count;
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            DB::table('users')->where('id', '=', $touser_id)->update($saveData);
        }
    }
}
