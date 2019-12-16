<?php


namespace App\Dao\Social;


use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\UserDao;
use App\Models\Social\SocialFollow;
use App\Models\Social\SocialFollowed;
use App\Models\Social\SocialLike;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class SocialDao
{

    public function follow($userId, $toUserId)
    {
        DB::beginTransaction();
        try {
            $follow = SocialFollow::create([
                    'user_id' => $userId,
                    'to_user_id' => $toUserId
                ]
            );

            $followed = SocialFollowed::create([
                    'user_id' => $toUserId,
                    'from_user_id' => $userId
                ]
            );

            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }


    public function like($userId, $toUserId)
    {
        try {
            SocialLike::create([
                    'user_id' => $userId,
                    'to_user_id' => $toUserId
                ]
            );
            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * 用户获得的赞
     * @param $userId
     * @return mixed
     */
    public function getLike($userId)
    {
        return SocialLike::where('to_user_id', $userId)->count();
    }

    /**
     * 获得用户关注的列表
     * @param $userId
     * @return mixed
     */
    public function getFollow($userId)
    {
        return SocialFollow::where('user_id',$userId)->get();
    }

    public function getUserDetail($userId){
        $userDao = new UserDao();
        $studentDao = new StudentProfileDao();
        $userObj = $userDao->getUserById($userId);
        return ['user_id' => $userId,
                'user_name'=> $userObj->name,
                'user_avatar' =>asset($studentDao->getStudentInfoByUserId($userId)->avatar)];
    }

    public function getUserList($userId, $column='to_user_id')
    {
        $data = [];
        if ($column == 'to_user_id') {
            $collections = $this->getFollow($userId);
        } else {
            $collections = $this->getFollowed($userId);
        }
        foreach ($collections as $collection) {
            $data[] = $this->getUserDetail($collection->$column);
        }
        return $data;
    }
    /**
     * 获得所有的粉丝数量
     * @param $userId
     * @return mixed
     */
    public function getFollowedCount($userId)
    {
        return SocialFollowed::where('user_id', $userId)->count();
    }

    /**
     * 获得用户的所有粉丝
     * @param $userId
     * @return mixed
     */
    public function getFollowed($userId) {
        return SocialFollowed::where('user_id', $userId)->get();
    }

    public function unFollow($userId, $toUserId)
    {
        DB::beginTransaction();
        try {
            $follow = SocialFollow::where('user_id', $userId)
                    ->where('to_user_id', $toUserId)->delete();
            if ($follow) {
                $followed = SocialFollowed::where('user_id', $toUserId)
                        ->where('from_user_id', $userId)->delete();

                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    public function unLike($userId, $toUserId)
    {
        try {
            SocialLike::where('user_id', $userId)
                ->where('to_user_id', $toUserId)->delete();
            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

}
