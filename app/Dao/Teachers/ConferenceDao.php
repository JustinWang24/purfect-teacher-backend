<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/23
 * Time: 下午4:04
 */
namespace App\Dao\Teachers;

use App\User;
use App\Utils\Misc\ConfigurationTool;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Teachers\Conference;
use App\Utils\ReturnData\MessageBag;
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


    /**
     * 待完成会议
     * @param User $user
     * @return mixed
     */
    public function unfinishedConference(User $user) {
        $field = ['conference_id', 'status', 'begin', 'end'];
        $now = Carbon::now()->toDateTimeString();
        $map = [['user_id','=',$user->id], ['to','>',$now]];
        $list = ConferencesUser::where($map)->select($field)
            ->orderBy('from','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        $list = $this->dataDispose($list);

        return $list;
    }


    /**
     * 已完成的会议
     * @param User $user
     * @return mixed
     */
    public function accomplishConference(User $user) {
        $now = Carbon::now()->toDateTimeString();
        $field = ['conference_id', 'status', 'begin', 'end'];
        $map = [['user_id','=',$user->id], ['to','<',$now]];
        $list = ConferencesUser::where($map)->select($field)
            ->orderBy('from','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        $list = $this->dataDispose($list);

        return $list;
    }


    /**
     * 数据处理
     * @param $list
     * @return mixed
     */
    public function dataDispose($list) {
        foreach ($list as $key => $val) {

            if($val['status'] == ConferencesUser::SIGN_IN) {
                $val['begin'] = Carbon::parse($val->begin)->format('H:i');
            }

            if($val['status'] == ConferencesUser::SIGN_OUT) {
                $val['begin'] = Carbon::parse($val->begin)->format('H:i');
                $val['end'] = Carbon::parse($val->end)->format('H:i');
            }

            $conference = $val->conference;
            $parse = Carbon::parse($conference->from);
            $val['date'] = $parse->format('Y-m-d');
            $conference['from'] = $parse->format('H:i');
            $conference['to'] = Carbon::parse($conference->to)->format('H:i');
            $conference->room;
            $conference->user_field = ['name'];
            $conference->user;
            $list[$key]['id'] = $val['conference_id'];
            unset($val['conference_id']);
            unset($conference['user_id']);
            unset($conference['room_id']);
        }

        return $list;
    }


    /**
     * 自己创建的会议
     * @param User $user
     * @return mixed
     */
    public function oneselfCreateConference(User $user) {
        $now = Carbon::now()->toDateTimeString();
        $field = ['id', 'title', 'user_id', 'room_id', 'status', 'from', 'to'];
        $map = ['user_id'=>$user->id];
        $list = Conference::where($map)->select($field)
            ->orderBy('from','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);

        foreach ($list as $key => $value){

            if($value->status == Conference::STATUS_CHECK) {
                if($now <= $value->from) {
                    $list[$key]['status'] = Conference::STATUS_WAITING;
                }
                if($value->from < $now && $now < $value->to) {
                    $list[$key]['status'] = Conference::STATUS_UNDERWAY;
                }

                if($now >=$value->to) {
                    $list[$key]['status'] = Conference::STATUS_FINISHED;
                }
            }

            $parse = Carbon::parse($value->from);
            $list[$key]['date'] = $parse->format('Y-m-d');
            $list[$key]['from'] = $parse->format('H:i');
            $list[$key]['to'] = Carbon::parse($value->to)->format('H:i');

            $value->user_field = 'name';
            $value->user;
            $value->room;
            unset($value['user_id']);
            unset($value['room_id']);
        }

        return $list;

    }


    /**
     * @param $id
     * @return mixed
     */
    public function getConferenceById($id) {
        return Conference::where('id', $id)->first();
    }


}
