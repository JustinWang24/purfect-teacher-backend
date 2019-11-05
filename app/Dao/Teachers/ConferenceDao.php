<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/23
 * Time: 下午4:04
 */
namespace App\Dao\Teachers;

use App\User;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;
use App\Models\Teachers\Conference;
use App\Models\Teachers\ConferencesUser;
use App\Models\Teachers\ConferencesMedia;

class ConferenceDao
{

    /**
     * @param User $user
     * @param $map
     * @param string $schoolId
     * @return mixed
     */
    public function getConferenceListByUser($user, $map,$schoolId='')
    {
        if($user->isSchoolAdminOrAbove()) {
            $map['school_id'] = $schoolId;
        }
        $model = new Conference();
        $list = $model->where($map)->with('user')->get();
        return $list;
    }


    /**
     * 创建会议
     * @param $data
     * @return mixed
     */
    public function createConference($data)
    {
        return Conference::create($data);
    }


    /**
     * 创建会议媒体信息
     * @param $data
     * @return mixed
     */
    public function createConferenceMedias($data)
    {
        return ConferencesMedia::create($data);
    }



    /**
     * 创建参会人员
     * @param $data
     * @return mixed
     */
    public function createConferenceUser($data)
    {

        return ConferencesUser::create($data);
    }


    /**
     * 获取参会人员
     * @param $from
     * @param $to
     * @param $schoolId
     * @return mixed
     */
    public function getConferenceUser($from,$to,$schoolId)
    {
        $map = [['school_id', '=', $schoolId],['from', '>=', $from]];
        $map2 = [['school_id', '=', $schoolId],['to', '<=', $to]];
        return ConferencesUser::where($map)->orwhere($map2)->get()->toArray();
    }

    /**
     * 添加会议流程
     * @param array $conferenceData 会议数据
     * @return MessageBag
     */
    public function addConferenceFlow($conferenceData)
    {
        try{
            DB::beginTransaction();
            $s1 = $this->createConference($conferenceData);
            if(!$s1->id) {
                throw new \Exception('创建会议失败');
            }

            foreach ($conferenceData['participant'] as $key => $val) {
                $conferenceUserData = [
                    'conference_id' => $s1->id,
                    'user_id'       => $val,
                    'school_id'     => $conferenceData['school_id'],
                    'status'        => 0,
                    'date'          => $conferenceData['date'],
                    'from'          => $conferenceData['from'],
                    'to'            => $conferenceData['to'],
                ];
                $s2 = $this->createConferenceUser($conferenceUserData);
                if(!$s2->id)
                {
                    throw new \Exception('邀请人添加失败');
                }
            }

            $medias = ['conference_id'=>$s1->id,'media_id'=>$conferenceData['media_id']];
            $s3 = $this->createConferenceMedias($medias);
            if(!$s3->id)
            {
                throw new \Exception('添加会议媒体关联失败');
            }
            DB::commit();

            return new MessageBag(1000,'创建成功');

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $msg = $e->getMessage();
//            return ['code'=>0,'msg'=>$msg];
            return new MessageBag(999,$msg);
        }
    }


    /**
     * 获取会议列表
     * @param $map
     * @param $field
     * @param $groupBy
     * @return mixed
     */
    public function getConference($map,$field,$groupBy='')
    {
        $model = new Conference();
        $list = $model->where($map)->select($field)->get();
        if(!empty($groupBy))
        {
            $list = $list->groupBy($groupBy);
        }

        return $list;
    }



}
