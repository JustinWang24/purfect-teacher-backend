<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/12/19
 * Time: 5:16 PM
 */

namespace App\Http\Controllers\Teacher;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\NetworkDisk\MediaRequest;
use App\Models\NetworkDisk\Media;
use App\Utils\FlashMessageBuilder;
use Illuminate\Support\Str;

class StudentsController extends Controller
{

    public function edit_avatar(MediaRequest $request){
        if($request->method() === 'GET'){
            $this->dataForView['pageTitle'] = '学生档案照片管理';
            $this->dataForView['user'] = (new UserDao())->getUserByIdOrUuid($request->uuid());
            return view('student.profile.update_avatar', $this->dataForView);
        }
        else{
            $user = (new UserDao())->getUserByIdOrUuid($request->get('user')['id']);
            $file = $request->getFile();
            $path = Media::DEFAULT_UPLOAD_PATH_PREFIX.$user->id;
            $url = $file->storeAs($path, Str::random(10).'.'.$file->getClientOriginalExtension()); // 上传并返回路径
            $profile = $user->profile;
            $profile->avatar = str_replace('public/','storage/',$url);
            $profile->save();
            FlashMessageBuilder::Push($request, 'success','照片已更新');
            return redirect()->route('teacher.student.edit-avatar',['uuid'=>$user->id]);
        }
    }
}