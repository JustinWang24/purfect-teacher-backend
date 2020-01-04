<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/23
 * Time: 下午4:04
 */
namespace App\Dao\Teachers;

use App\User;
use Carbon\Carbon;
use App\Utils\JsonBuilder;
use Illuminate\Support\Facades\DB;
use App\Models\Teachers\Conference;
use App\Utils\ReturnData\MessageBag;
use App\Utils\Misc\ConfigurationTool;
use App\Models\Teachers\ConferencesUser;

class ConferenceDao
{

    /**
     * @param $id
     * @return mixed
     */
    public function getConferenceById($id) {
        return Conference::where('id', $id)->first();
    }

    /**
     * @param string $schoolId
     * @return mixed
     */
    public function getConferenceBySchoolId($schoolId)
    {
        $map = ['school_id'=>$schoolId];
        return Conference::where($map)
            ->orderBy('to','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 创建会议
     * @param array $data 会议数据
     * @return MessageBag
     */
    public function addConference($data) {
        $user = explode(',',$data['conference']); // 参会人员
        unset($data['conference']);
        try{
            DB::beginTransaction();
            $re = Conference::create($data);

            foreach ($user as $key => $val) {
                $userData = [
                    'conference_id' => $re->id,
                    'user_id'       => $val,
                    'school_id'     => $data['school_id'],
                ];
                ConferencesUser::create($userData);
            }

            // 暂时没有会议媒体数据

            DB::commit();

            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功');

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $msg = $e->getMessage();
            return new MessageBag(JsonBuilder::CODE_ERROR,$msg);
        }
    }


    /**
     * 编辑会议
     * @param array $data
     * @return MessageBag
     */
    public function updConference($data) {
        $user = explode(',',$data['conference']); // 参会人员

        unset($data['conference']);
        try{
            DB::beginTransaction();
            // 修改会议主表
            Conference::where(['id'=>$data['id']])->update($data);
            // 删除参会人员
            ConferencesUser::where(['conference_id'=>$data['id']])->delete();
            // 创建参会人员
            foreach ($user as $key => $val) {
                $userData = [
                    'conference_id' => $data['id'],
                    'user_id'       => $val,
                    'school_id'     => $data['school_id'],
                ];
                ConferencesUser::create($userData);
            }

            DB::commit();
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'编辑成功');

        }catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return new MessageBag(JsonBuilder::CODE_ERROR,$msg);
        }
    }


    /**
     * 删除会议
     * @param $id
     * @return MessageBag
     */
    public function deleteConference($id) {
        $messageBag = new MessageBag();
        try {
            DB::beginTransaction();
            // 删除参会人员
            ConferencesUser::where(['conference_id'=>$id])->delete();
            // 删除会议
            Conference::where(['id'=>$id])->delete();
            DB::commit();
            $messageBag->setMessage('删除成功');
        }catch (\Exception $e) {
            DB::rollBack();
            $messageBag->setMessage($e->getMessage());
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
        }
        return $messageBag;
    }


    /**
     * 待完成会议
     * @param User $user
     * @return mixed
     */
    public function unfinishedConference(User $user) {
        $field = ['conferences_users.status', 'begin', 'end', 'conference_id'];
        $now = Carbon::now()->toDateTimeString();
        $map = [
            ['conferences_users.user_id','=',$user->id],
            ['conferences.to','>',$now],
            ['conferences.status','=',Conference::STATUS_CHECK]
        ];

        $list = ConferencesUser::
            join('conferences', function($join) use ($map){
                $join->on('conferences.id','=','conferences_users.conference_id')
                    ->where($map);
            })
            ->select($field)
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
        $field = ['conferences_users.status', 'begin', 'end', 'conference_id'];
        $map = [
            ['conferences_users.user_id','=',$user->id],
            ['conferences.to','<',$now],
            ['conferences.status','=',Conference::STATUS_CHECK]
        ];
        $list = ConferencesUser::
            join('conferences', function($join) use ($map){
                $join->on('conferences.id','=','conferences_users.conference_id')
                    ->where($map);
            })
            ->select($field)
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
//            dd($conference);
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


}
