<?php


namespace App\Http\Controllers\Api\OA;

use App\Dao\OA\InternalMessageDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\OA\InternalMessage;
use App\Models\OA\InternalMessageFile;
use App\Utils\JsonBuilder;

class InternalMessageController extends Controller
{

    /**
     * 发信
     * @param MyStandardRequest $request
     * @return string
     */
    public function addMessage(MyStandardRequest $request)
    {
        $user        = $request->user();
        $collectId   = $request->get('collectId');
        $collectUser = $request->get('collectUser');
        $title       = $request->get('title');
        $content     = $request->get('content');
        $type        = $request->get('type');
        $isRelay     = $request->get('isRelay');
        $relayId     = $request->get('relayId');
        $files       = $request->file('image');
        $isFile      = $request->file('isFile');

        $fileArr = [];
        foreach ($files as $key => $file) {
            $fileArr[$key]['name'] = $file->getClientOriginalName();
            $fileArr[$key]['type'] = $file->extension();
            $fileArr[$key]['size'] = getFileSize($file->getSize());
            $fileArr[$key]['path'] = InternalMessage::internalMessageUploadPathToUrl($file->store(InternalMessage::DEFAULT_UPLOAD_PATH_PREFIX));
        }

        $dao = new InternalMessageDao;

        $data = [
            'user_id'           => $user->id,
            'collect_user_id'   => $collectId,
            'collect_user_name' => $collectUser,
            'title'             => $title,
            'content'           => $content,
            'type'              => $type,
            'is_relay'          => $isRelay,
            'is_file'           => $isFile,
        ];

        $result = $dao->create($data, $fileArr);
        if ($result) {
            return JsonBuilder::Success('添加成功');
        } else {
            return JsonBuilder::Error('添加失败');
        }
    }

    /**
     * 信件列表
     * @param MyStandardRequest $request
     */
    public function messageList(MyStandardRequest $request)
    {

    }

    /**
     * 获取当前学校所有老师
     * @param MyStandardRequest $request
     * @return string
     */
    public function getTeachers(MyStandardRequest $request)
    {
        $user     = $request->user();
        $schoolId = $user->getSchoolId();

        $dao      = new UserDao;
        $teachers = $dao->getTeachersBySchool($schoolId);

        $data = [];
        foreach ($teachers as $key => $teacher) {
            $data[$key]['id']     = $teacher->id;
            $data[$key]['name']   = $teacher->name;
            $data[$key]['avatar'] = $teacher->user->profile->avatar;
        }

        return JsonBuilder::Success($data);
    }

}
