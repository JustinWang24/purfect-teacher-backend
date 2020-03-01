<?php

namespace App\Http\Controllers\Admin;

use App\Dao\Users\UserDao;
use App\Http\Requests\SchoolRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Models\School;
use App\Models\Schools\SchoolResource;
use App\User;
use App\Utils\FlashMessageBuilder;

class SchoolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 加载用户表的编辑页面
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(SchoolRequest $request){
        $this->dataForView['school'] = new School();
        return view('admin.schools.add', $this->dataForView);
    }

    /**
     * 加载用户表的编辑页面
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        $this->dataForView['school'] = $dao->getSchoolByUuid($request->uuid());
        return view('admin.schools.edit', $this->dataForView);
    }

    /**
     * 加载用户表的编辑页面
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        $schoolData = $request->get('school');
        $schoolLogo = $request->file('logo')->store(SchoolResource::DEFAULT_UPLOAD_PATH_PREFIX);
        if ($schoolLogo) {
            $schoolData['logo'] = SchoolResource::schoolResourceUploadPathToUrl($schoolLogo);
        }

        if(isset($schoolData['uuid'])){
            $result = $dao->updateSchool($schoolData);
        }
        else{
            $result = $dao->createSchool($schoolData);
        }

        if($result){
            // 保存成功
            FlashMessageBuilder::Push($request,FlashMessageBuilder::SUCCESS, '学校"'.$schoolData['name'].'"信息保存成功!');
        }
        else{
            FlashMessageBuilder::Push($request,FlashMessageBuilder::DANGER, '学校"'.$schoolData['name'].'"信息保存失败!');
        }
        return redirect()->route('home');
    }

    public function edit_school_manager(SchoolRequest $request){
        $this->dataForView['pageTitle'] = '编辑学校管理员';
        $this->dataForView['user'] = (new UserDao())->getUserByUuid($request->get('user'));
        $this->dataForView['school'] = (new SchoolDao())->getSchoolByUuid($request->get('school'));
        return view('admin.schools.add_school_manager',$this->dataForView);
    }

    public function create_school_manager(SchoolRequest $request){
        if($request->method() === 'GET'){
            $this->dataForView['pageTitle'] = '创建学校管理员';
            $this->dataForView['user'] = new User();
            $this->dataForView['school'] = (new SchoolDao())->getSchoolByUuid($request->get('school'));
            return view('admin.schools.add_school_manager',$this->dataForView);
        }
        elseif($request->method() === 'POST'){
            $school = (new SchoolDao())->getSchoolByUuid($request->get('school_uuid'));
            $userData = $request->get('user');
            // 检查是创建, 还是更新
            if(empty($request->get('user_uuid'))){
                // 创建学校管理员的操作
                if($school){
                    $userDao = new UserDao();
                    $result = $userDao->createSchoolManager(
                        $school,
                        $userData['mobile'],
                        $userData['password'],
                        $userData['name'],
                        $userData['email']
                    );
                    if($result->isSuccess()){
                        FlashMessageBuilder::Push($request,FlashMessageBuilder::SUCCESS,'学校管理员创建成功');
                        return redirect()->route('home');
                    }
                    else{
                        dd($result->getMessage());
                    }
                }
            }
            else{
                // 更新学校管理员账户
                if($school){
                    $userDao = new UserDao();
                    $user = $userDao->getUserByUuid($request->get('user_uuid'));
                    if($user){
                        $userData = $request->get('user');
                        $userDao->updateUser(
                            $user->id,
                            $userData['mobile'],
                            $userData['password'],
                            $userData['name'],
                            $userData['email']
                        );
                    }

                    return redirect()->route('home');
                }
            }
        }
    }
}
