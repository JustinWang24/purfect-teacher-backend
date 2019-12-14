<?php


namespace App\Dao\Social;


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
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $follow = SocialFollow::create([
                    'user_id' => $userId,
                    'to_user_id' => $toUserId
                ]
            );
            if ($follow) {
                $followed = SocialFollowed::create([
                        'user_id' => $toUserId,
                        'from_user_id' => $userId
                    ]
                );

                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            } else {
                DB::rollBack();
                $messageBag->setMessage('关系创建失败');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }


    public function like($userId, $toUserId)
    {
        return SocialLike::create([
                'user_id' => $userId,
                'to_user_id' => $toUserId
            ]
        );
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
        return SocialFollow::where('user_id',$userId)->all();
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
        return SocialFollowed::where('user_id', $userId)->all();
    }

    public function unFollow($userId, $toUserId)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $follow = SocialFollow::where('user_id', $userId)
                    ->where('to_user_id', $toUserId)->delete();
            if ($follow) {
                $followed = SocialFollowed::where('user_id', $toUserId)
                        ->where('from_user_id', $userId)->delete();

                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            } else {
                DB::rollBack();
                $messageBag->setMessage('关系删除失败');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    public function unLike($userId, $toUserId)
    {
        return SocialLike::where('user_id', $userId)
                ->where('to_user_id', $toUserId)->delete();
    }
}
