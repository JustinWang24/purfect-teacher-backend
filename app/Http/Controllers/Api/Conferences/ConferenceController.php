<?php


namespace App\Http\Controllers\Api\Conferences;


use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Teachers\ConferenceDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ConferenceRequest;

class ConferenceController extends Controller
{

    /**
     * 未完成的会议
     * @param ConferenceRequest $request
     * @return string
     */
    public function unfinished(ConferenceRequest $request) {
        // todo 判断是否为教师或更高权限

        $user = $request->user();
        // 查询当前用户受邀未结束的会议
        $dao = new ConferenceDao();
        $result = $dao->unfinishedConference($user);
        $data = pageReturn($result);
//        dd($data);
        return JsonBuilder::Success($data['list']);
    }


    /**
     * 已完成的会议
     * @param ConferenceRequest $request
     * @return string
     */
    public function accomplish(ConferenceRequest $request) {

        // todo 判断是否为教师或更高权限
        $user = $request->user();
        // 查询当前用户受邀结束会议
        $dao = new ConferenceDao();
        $result = $dao->accomplishConference($user);

        $data = pageReturn($result);
        dd($data);
        return JsonBuilder::Success($data);
    }



    /**
     * 自己创建的会议
     * @param ConferenceRequest $request
     * @return string
     */
    public function oneselfCreate(ConferenceRequest $request) {

        $user = $request->user();
        $dao = new ConferenceDao();

        $result = $dao->oneselfCreateConference($user);
        $data = pageReturn($result);
        return JsonBuilder::Success($data);
    }


    /**
     * 会议详情
     * @param ConferenceRequest $request
     * @return string
     */
    public function conferenceInfo(ConferenceRequest $request) {
        $conferenceId = $request->get('id');
        $dao = new ConferenceDao();
        $info = $dao->getConferenceById($conferenceId);
        $info->user_field = 'name';
        $info->user;
        $info->room;
        $info['date'] = Carbon::parse($info->from)->format('Y-m-d');
        $info['from'] = Carbon::parse($info->from)->format('H:i');
        $info['to'] = Carbon::parse($info->to)->format('H:i');
        unset($info['school_id']);
        unset($info['user_id']);
        unset($info['room_id']);
        $medias = $info->medias;
        foreach($medias as $key => $val) {
            $medias[$key] = env('APP_URL').$val->media->url;
        }
        $data = ['conference'=>$info];
        return JsonBuilder::Success($data);
    }

}
