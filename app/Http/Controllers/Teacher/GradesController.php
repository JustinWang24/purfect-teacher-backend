<?php
namespace App\Http\Controllers\Teacher;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\GradeRequest;
use App\Dao\Schools\GradeDao;
use App\Models\Schools\GradeManager;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;
use App\BusinessLogic\UsersListPage\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GradesController extends Controller
{
    /**
     * 设置为班长
     * @param GradeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function set_monitor(GradeRequest $request){
        if($request->isMethod('POST')){
            $adviserData = $request->getAdviserForm();

            $adviserData['monitor_name'] = explode(' - ',$adviserData['monitor_name'])[0];

            $result = (new GradeDao())->setAdviser($adviserData);

            return $result->isSuccess() ? JsonBuilder::Success():JsonBuilder::Error($result->getMessage());
        }
        elseif ($request->isMethod('GET')){
            $this->dataForView['pageTitle'] = '设置班长';
            $grade = (new GradeDao())->getGradeById($request->get('grade'));
            $this->dataForView['grade'] = $grade;

            if($grade->gradeManager){
                $gradeManager = $grade->gradeManager;
            }
            else{
                // 如果系主任记录还不存在, 那么构造一个新的
                $gradeManager = new GradeManager();
                $gradeManager->grade_id = $grade->id;
                $gradeManager->school_id = $request->session()->get('school.id');
                $gradeManager->adviser_id = 0;
                $gradeManager->adviser_name = '';
                $gradeManager->monitor_id = 0;
                $gradeManager->monitor_name = '';
            }
            $this->dataForView['gradeManager'] = $gradeManager;
            return view('teacher.grade.set_monitor',$this->dataForView);
        }
    }

    public function edit(GradeRequest $request){
        $profileDao = new TeacherProfileDao();

        if($request->method()==='GET'){
            if($request->uuid()){
                $profile = $profileDao->getTeacherProfileByTeacherIdOrUuid($request->uuid());
            }
            else{
                $profile = $request->user()->profile;
            }

            $this->dataForView['profile'] = $profile;
            $this->dataForView['pageTitle'] = '编辑用户档案';
            return view('teacher.profile.edit_profile', $this->dataForView);
        }
        elseif ($request->method()==='POST'){
            $profileData = $request->get('profile');
            $teacherData = $request->get('teacher');

            $profile = $profileDao->getTeacherProfileByTeacherIdOrUuid($profileData['uuid']);
            foreach ($profileData as $field=>$value) {
                $profile->$field = $value;
            }
            $profile->save();

            $user = $profile->user;
            foreach ($teacherData as $k=>$v) {
                $user->$k = $v;
            }
            $user->save();
            FlashMessageBuilder::Push($request, 'success',$user->name.'档案数据更新成功');
            return redirect()->route('teacher.profile.edit',['uuid'=>$profileData['uuid']]);
        }
    }

    /**
     * 更新老师密码的操作
     * @param GradeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|string
     */
    public function update_password(GradeRequest $request){
        if($request->method() === 'GET'){
            if(Auth::user()->isSchoolAdminOrAbove() || Auth::user()->id === $request->get('uuid')){
                // 有修改密码的权限
                $this->dataForView['user_id'] = $request->get('uuid');
                return view('teacher.profile.update_password',$this->dataForView);
            }
        }
        elseif ($request->method() === 'POST'){
            if(Auth::user()->isSchoolAdminOrAbove() || Auth::user()->id === $request->get('uuid')){
                // 有修改密码的权限
                $userData = $request->get('user');
                $user = (new UserDao())->getTeacherByIdOrUuid($userData['id']);
                if($user){
                    $user->password = Hash::make($userData['password']);
                    $user->save();
                    FlashMessageBuilder::Push($request,'success',$user->name.'的密码更新成功');
                }
                else{
                    FlashMessageBuilder::Push($request,'error','系统繁忙, 请稍候再试');
                }
                return Auth::user()->isSchoolAdminOrAbove() ? redirect()->route('school_manager.school.teachers')
                    : route('home');
            }
        }

        return '你无权进行此操作';
    }

    /**
     * 从班级的角度, 加载给定班级的学生列表
     * @param GradeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users(GradeRequest $request){
        $logic = Factory::GetLogic($request);
        $this->dataForView['parent'] = $logic->getParentModel();
        $this->dataForView['appendedParams'] = $logic->getAppendedParams();
        $this->dataForView['returnPath'] = $logic->getReturnPath();
        return view($logic->getViewPath(),array_merge($this->dataForView, $logic->getUsers()));
    }

    public function load_students(GradeRequest $request){
        $gradeDao = new GradeDao();
        $grade = $gradeDao->getGradeById($request->getGradeId());

        // 找到这个班级所有的学生
        $students = $grade->allStudents();

        return JsonBuilder::Success([
            'grade'=>$grade,
            'students'=>$students
        ]);
    }
}