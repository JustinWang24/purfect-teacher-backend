<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/10
 * Time: 下午12:54
 */

namespace App\Http\Controllers\Api\OA;


use App\Events\SystemNotification\OaMeetingEvent;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use App\Utils\JsonBuilder;
use App\Models\Schools\Room;
use App\Dao\Schools\RoomDao;
use App\Dao\OA\NewMeetingDao;
use App\Models\OA\NewMeeting;
use App\Models\OA\NewMeetingUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\MeetingRequest;

class NewMeetingController extends Controller
{

    /**
     * 会议设置
     * @param MeetingRequest $request
     * @return string
     */
    public function meetingSet(MeetingRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $date = $request->get('date',Carbon::now()->toDateString());

        $roomDao = new RoomDao();
        $map = ['school_id'=>$schoolId, 'type' =>Room::TYPE_MEETING_ROOM];
        $rooms = $roomDao->getRooms($map);
        $dao = new NewMeetingDao();
        $return = $dao->getMeetingByDate($schoolId, $date);
        $result = [];
        foreach ($rooms as $key => $item) {
            $result[$key] = [
                'room_id'=>$item->id,
                'img' => $item->url,
                'name' => $item->description,
                'seats' => $item->seats,
                'start' => '8:00',
                'end' => '18:00',
                'time'=>[],
            ];
            foreach ($return as $k => $v) {
                if($item->id == $v->room_id) {
                    $result[$key]['time'][$k]['meet_start'] = $v->meet_start;
                    $result[$key]['time'][$k]['meet_end'] = $v->meet_end;
                }
            }

        }
        return JsonBuilder::Success(array_merge($result));
    }


    /**
     * 创建会议
     * @param MeetingRequest $request
     * @return string
     */
    public function addMeeting(MeetingRequest $request) {
        $user = $request->user();
        $data = $request->all();

        // 签退时间应大于会议时间
        if($data['signout_status'] == NewMeeting::SIGNOUT) {
            // 签退开始时间判断
            if ($data['signin_status'] == NewMeeting::SIGNIN) {
                if ($data['signout_start'] < $data['signin_end']) {
                    return JsonBuilder::Error('签退开始时间应大于会议签到结束时间');
                }
            } else {
                if ($data['signout_start'] < $data['meet_start']) {
                    return JsonBuilder::Error('签退开始时间应大于会议开始时间');
                }
            }
            // 签退结束时间判断
            if ($data['meet_end'] > $data['signout_end']) {
                return JsonBuilder::Error('签退结束时间应大于会议结束时间');
            }
        }

        $data['user_id'] = $user->id;
        $data['school_id'] = $request->user()->getSchoolId();
        $users = $data['user'];
        unset($data['user']);
        unset($data['file']);
        $file = $request->file('file');
        $dao = new NewMeetingDao();

        array_push($users, $data['approve_userid']);
        $users = array_unique($users);

        $result = $dao->addMeeting($data, $users, $file, $user);

        if($result->isSuccess()) {
            $meetId = $result->getData()['meet_id'];
            // 自定义地点
            if($data['type'] == NewMeeting::TYPE_CUSTOM_ROOM) {
                //通知负责人和成员
                foreach ($users as $userid) {
                    event(new OaMeetingEvent($userid, $meetId));
                }
            }
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


    /**
     * 待完成会议
     * @param MeetingRequest $request
     * @return string
     */
    public function unfinished(MeetingRequest $request) {
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();

        $return = $dao->unfinishedMeet($userId);
        if(is_null($return)) {
            $data = [
                'currentPage' => 0,
                'lastPage'    => 0,
                'total'       => 0,
                'list'        => []
            ];
            return JsonBuilder::Success($data);
        }
        $result = pageReturn($return);
        $data = [];
        foreach ($result['list'] as $key => $item) {
            $status =NewMeetingUser::CLOSE; // 关闭
            $meetUsers = array_merge($item->meetUsers->where('user_id',$userId)->toArray());
            // 判断是否需要签到
            if($item->signin_status == NewMeeting::SIGNIN) {
                // 判断是否签到
                if($meetUsers[0]['signin_status'] == NewMeetingUser::UN_SIGNIN) {
                    $status = NewMeetingUser::UN_SIGNIN;
                }

            }
            // 判断是否已签到和是否需要签退
            if ( $status == NewMeetingUser::CLOSE && $item->signout_status == NewMeeting::SIGNOUT) {
                // 判断是否签退
                if($meetUsers[0]['signout_status'] == NewMeetingUser::UN_SIGNOUT) {
                    $status = NewMeetingUser::NORMAL_SIGNIN;
                }
            }

            $data[] = [
                'meet_id' => $item->id,
                'meet_title' => $item->meet_title,
                'approve_user' => $item->approve->name,
                'room' => $item->room_id ? $item->room->name : $item->room_text,
                'meet_time' => $item->getMeetTime(),
                'signin_time' =>'',
                'signin_status' => $status,
            ];
            if($item->signin_status == NewMeeting::SIGNIN) {
                $data[$key]['signin_time'] = $item->getSignInTime();
            }
        }
        $result['list'] = $data;
        return JsonBuilder::Success($result);
    }


    /**
     * 已完成会议
     * @param MeetingRequest $request
     * @return string
     */
    public function accomplish(MeetingRequest $request) {
        $user = $request->user();
        $dao = new NewMeetingDao();
        $return = $dao->accomplishMeet($user);
        if(is_null($return)) {
            $data = [
                'currentPage' => 0,
                'lastPage'    => 0,
                'total'       => 0,
                'list'        => []
            ];
            return JsonBuilder::Success($data);
        }
        $result = pageReturn($return);
        $data = [];
        foreach ($result['list'] as $key => $item) {
            $meetUser = $item->meetUsers->first();
            $data[] = [
                'meet_id' => $item->id,
                'meet_title' => $item->meet_title,
                'approve_user' => $item->approve->name,
                'room' => $item->room_id ? $item->room->name : $item->room_text,
                'meet_time' => $item->getMeetTime(),
                'signin_time' => '',
                'signout_time' => '',
                'is_signin' => 0,
                'is_signout' => 0,
                'signin_status' =>  $meetUser->signin_status,
                'signout_status' => $meetUser->signout_status,
            ];
            // 判断是否需要签到
            if($item->signin_status == NewMeeting::SIGNIN) {
                $data[$key]['signin_time'] = $item->getSignInTime();
                $data[$key]['is_signin'] = NewMeeting::SIGNIN;
            }
            // 判断是否需要签退
            if($item->signout_status == NewMeeting::SIGNOUT) {
                $data[$key]['signout_time'] = $item->getSignOutTime();
                $data[$key]['is_signout'] = NewMeeting::SIGNOUT;
            }
        }
        $result['list'] = $data;
        return JsonBuilder::Success($result);
    }


    /**
     * 自己创建的
     * @param MeetingRequest $request
     * @return string
     */
    public function oneselfCreate(MeetingRequest $request) {
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();
        $return = $dao->oneselfCreateMeet($userId);

        $result = pageReturn($return);
        $data = [];
        foreach ($result['list'] as $key => $item) {

            $now = Carbon::now()->toDateTimeString();
            // 通过
            if($item->status == NewMeeting::STATUS_PASS) {
                // 判断是否需要签退 不需要 用会议结束时间判断是否结束
                if($item->signout_status == NewMeeting::NOT_SIGNOUT) {
                    if($now < $item->meet_start) {
                        $status = NewMeeting::STATUS_WAIT;
                    } elseif ($now > $item->meet_start && $now < $item->meet_end) {
                        $status = NewMeeting::STATUS_UNDERWAY;
                    } else {
                        $status = NewMeeting::STATUS_FINISHED;
                    }
                } else {
                    // 用签退时间判断会议是否结束
                    if($now < $item->meet_start) {
                        $status = NewMeeting::STATUS_WAIT;
                    } elseif ($now > $item->meet_start && $now < $item->signout_end) {
                        $status = NewMeeting::STATUS_UNDERWAY;
                    } else {
                        $status = NewMeeting::STATUS_FINISHED;
                    }
                }

            } else {
                $status = $item->status;
            }

            $data[] = [
                'meet_id' => $item->id,
                'meet_title' => $item->meet_title,
                'approve_user' => $item->approve->name,
                'room' => $item->room_id ? $item->room->name : $item->room_text,
                'meet_time' => $item->getMeetTime(),
                'signin_time' => '',
                'status' => $status,
            ];
            if($item->signin_status == NewMeeting::SIGNIN) {
                $data[$key]['signin_time'] = $item->getSignInTime();
            }
        }
        $result['list'] = $data;
        return JsonBuilder::Success($result);
    }


    /**
     * 会议详情
     * @param MeetingRequest $request
     * @return string
     */
    public function meetDetails(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();
        $info = $dao->meetDetails($meetId);
        if(is_null($info)) {
            return JsonBuilder::Error('该会议不存在');
        }

        $fields = [];
        foreach ($info->files as $item) {
            $fields[] = $item->url;
        }

        $status = 0;  // 隐藏
        $meetUser = $info->meetUsers->where('user_id',$userId)->first();
        $now = Carbon::now();
        if(!is_null($meetUser)) {
            // 需要签到
            if($info['signin_status'] == NewMeeting::SIGNIN) {
                // 当前时间小于签到的结束时间
                if($now < $info->signin_end) {
                    // 未签到
                    if($meetUser->signin_status == NewMeetingUser::UN_SIGNIN) {
                        $status = 1; // 签到
                    } else {
                        $status = 0; // 隐藏
                    }
                }

            }

            // 需要签退&& 已签退
            if($status == 0 && $info['signout_status'] == NewMeeting::SIGNOUT) {
                // 当前时间小于签退的结束时间
                if($now < $info->signout_end) {
                    if($meetUser->signout_status == NewMeetingUser::UN_SIGNOUT) {
                        $status = 2; // 签退
                    } else {
                        $status = 0; //隐藏
                    }

                }
            }
        }


        $result = [
            'meet_id' => $info->id,
            'meet_title' => $info->meet_title,
            'room' => $info->room_id? $info->room->name : $info->room_text,
            'meet_time' => $info->getMeetTime(),
            'approve_userid' => $info->approve_userid,
            'approve' => $info->approve->name,
            'user_num' => $info->meetUsers->count(),
            'signin_time' => '',
            'signout_time' => '',
            'meet_content' => $info->meet_content,
            'fields' => $fields,
            'cause' => $info->cause,
            'signin_status' => $info->signin_status,
            'signout_status' => $info->signout_status,
            'status' => $status,
        ];

        // 判断是否需要签到
        if($info['signin_status'] == NewMeeting::SIGNIN) {
            $result['signin_time'] = $info->getSignInTime();
        }
        // 判断是否需要签退
        if($info['signout_status'] == NewMeeting::SIGNOUT) {
            $result['signout_time'] = $info->getSignOutTime();
        }

        return JsonBuilder::Success($result);
    }


    /**
     * 会议签到签退
     * @param MeetingRequest $request
     * @return string
     */
    public function meetSignIn(MeetingRequest $request) {
        $userId = $request->user()->id;
        $meetId = $request->getMeetId();
        $type = $request->getType();
        $dao = new NewMeetingDao();
        $info = $dao->getMeetByMeetId($meetId);
        if(is_null($info)) {
            return JsonBuilder::Error('该会议不存在');
        }
        $meetUser = $info->meetUsers->where('user_id',$userId)->first();
        if(is_null($meetUser)) {
            return JsonBuilder::Error('您不是参会人员');
        }

        $now = Carbon::now()->toDateTimeString();

        if($type == 'signIn') {
            $title = '会议签到';
            if($now < $info->signin_start) {
                $status = 1; // 待开始
            } elseif ($now > $info->signin_start && $now < $info->signin_end) {
                $status = 2; // 进行中
            } else {
                $status = 3; // 已结束
            }
            if($meetUser->signin_status == 0) {
                $signin_status = 0; // 未签到
            } else {
                $signin_status = 1; // 已签到
            }

            $time = $info->getSignInTime();
        } else {
            $title = '会议签退';
            if($now < $info->signout_start) {
                $status = 1; // 待开始
            } elseif ($now > $info->signout_start && $now < $info->signout_end) {
                $status = 2; // 进行中
            } else {
                $status = 3; // 已结束
            }
            if($meetUser->signout_status == 0) {
                $signin_status = 0; // 未签退
            } else {
                $signin_status = 1; // 已签退
            }

            $time = $info->getSignOutTime();
        }

        $data = [
            'title' => $title,
            'signin_status' => $signin_status,
            'meet_title' => $info->meet_title,
            'meet_time' => $info->getMeetTime(),
            'signin_time' => $time,
            'status' => $status,
        ];

        return JsonBuilder::Success($data);
    }


    /**
     * 保存签到
     * @param MeetingRequest $request
     * @return string
     */
    public function saveSignIn(MeetingRequest $request) {
        $userId = $request->user()->id;
        $meetId = $request->getMeetId();
        $type = $request->getType();

        $dao = new NewMeetingDao();
        $meet = $dao->getMeetByMeetId($meetId);
        if(is_null($meet)) {
            return JsonBuilder::Error('该会议不存在');
        }
        $meetUser = $meet->meetUsers->where('user_id',$userId)->first();
        if(is_null($meetUser)) {
            return JsonBuilder::Error('您不是参会人员');
        }

        $result = $dao->saveSignIn($meet, $meetUser->id, $type);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            return JsonBuilder::Success($msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }




    /**
     * 保存会议纪要
     * @param MeetingRequest $request
     * @return string
     */
    public function saveMeetSummary(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $summaries = $request->file('summary');
        if(count($summaries) > 7) {
            return JsonBuilder::Error('最多上传7张');
        }
        $userId = $request->user()->id;

        $dao = new NewMeetingDao();
        $result = $dao->saveMeetSummary($meetId,$userId,$summaries);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }


    /**
     * 获取会议纪要
     * @param MeetingRequest $request
     * @return string
     */
    public function getMeetSummary(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();
        $list = $dao->getMeetSummary($meetId, $userId);
        return JsonBuilder::Success($list);
    }


    /**
     * 已完成-签到记录
     * @param MeetingRequest $request
     * @return string
     */
    public function signInRecord(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $userId = $request->user()->id;
        $dao = new NewMeetingDao();
        $return = $dao->getMeetUser($meetId, $userId);
        $result = [
            'signin_status' => $return->signin_status,
            'signin_time' => $return->getUserSignInTime(),
            'signout_status' => $return->signout_status,
            'signout_time' => $return->getUserSignOutTime()
        ];

        return JsonBuilder::Success($result);
    }


    /**
     * 签到二维码
     * @param MeetingRequest $request
     * @return string
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     */
    public function signInQrCode(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new NewMeetingDao();
        $meet = $dao->getMeetByMeetId($meetId);
        if(is_null($meet)) {
            return JsonBuilder::Error('该会议不存在');
        }

        $codeStr = base64_encode(json_encode(
            ['meet_id' => $meetId,'type'=>'signIn']));

        $qrCode = new QrCode($codeStr);
        $qrCode->setSize(400);
        $qrCode->setLogoPath(public_path('assets/img/logo.png'));
        $qrCode->setLogoSize(60, 60);
        $code = 'data:image/png;base64,' . base64_encode($qrCode->writeString());

        $data = ['msg'=>'签到二维码','qrcode'=>$code];
        return JsonBuilder::Success($data);
    }


    /**
     * 签退二维码
     * @param MeetingRequest $request
     * @return string
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     */
    public function signOutQrCode(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new NewMeetingDao();
        $meet = $dao->getMeetByMeetId($meetId);
        if(is_null($meet)) {
            return JsonBuilder::Error('该会议不存在');
        }

        $codeStr = base64_encode(json_encode(
            ['meet_id' => $meetId,'type'=>'signOut']));

        $qrCode = new QrCode($codeStr);
        $qrCode->setSize(400);
        $qrCode->setLogoPath(public_path('assets/img/logo.png'));
        $qrCode->setLogoSize(60, 60);
        $code = 'data:image/png;base64,' . base64_encode($qrCode->writeString());

        $data = ['msg'=>'签退二维码','qrcode'=>$code];
        return JsonBuilder::Success($data);
    }


    /**
     * 我创建的 会议纪要
     * @param MeetingRequest $request
     * @return string
     */
    public function myMeetSummary(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new NewMeetingDao();
        $meet = $dao->getMeetByMeetId($meetId);
        if(is_null($meet)) {
            return JsonBuilder::Error('该会议不存在');
        }

        $summaries = $meet->summaries->groupBy('meet_user_id');
        $result = [];
        foreach ($summaries as $key => $item) {
            foreach ($item as $k => $val) {
                $result[$key]['meet_id'] = $val->meet_id;
                $result[$key]['user_id'] = $val->user_id;
                $result[$key]['user_name'] = $val->user->name;
                $result[$key]['created_at'] = $val->created_at;
                $result[$key]['summaries'][] = [
                    'summary_id' => $val->id,
                    'url' => $val->url,
                    'file_name' => $val->file_name,
                ];
            }

        }

        return JsonBuilder::Success(array_merge($result));
    }


    /**
     * 我创建的-签到记录
     * @param MeetingRequest $request
     * @return string
     */
    public function mySignInRecord(MeetingRequest $request) {
        $meetId = $request->getMeetId();
        $dao = new NewMeetingDao();
        $meet = $dao->getMeetByMeetId($meetId);
        if(is_null($meet)) {
            return JsonBuilder::Error('该会议不存在');
        }
        $unSignIn = $meet->meetUsers->where('signin_status',NewMeetingUser::UN_SIGNIN)->count();
        $signIn = $meet->meetUsers->where('signin_status',NewMeetingUser::NORMAL_SIGNIN)->count();
        $late = $meet->meetUsers->where('signin_status',NewMeetingUser::LATE)->count();
        $unSignOut = $meet->meetUsers->where('signout_status',NewMeetingUser::UN_SIGNOUT)->count();
        $signOut = $meet->meetUsers->where('signout_status',NewMeetingUser::NORMAL_SIGNOUT)->count();

        $stat = [
            'un_sign_in'=>$unSignIn, 'sign_in'=>$signIn, 'late'=>$late,
            'un_sign_out'=>$unSignOut, 'sign_out'=>$signOut
        ];


        $record = $dao->getMeetUserPageByMeetId($meetId);

        $record = pageReturn($record);

        $list = [];
        foreach ($record['list'] as $key => $item) {
            $list[] = [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'user_name' => $item->user->name,
                'avatar' => $item->user->profile->avatar,
                'signin_status' => $item->signin_status,
                'signout_status' => $item->signout_status,
            ];
        }

        $record['list'] = $list;

        $data = [
            'stat'=>$stat,
            'record'=>$record
        ];


        return JsonBuilder::Success($data);
    }



}
