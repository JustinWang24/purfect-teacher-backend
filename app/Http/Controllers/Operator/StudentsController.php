<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\GradeDao;
use App\Dao\Schools\MajorDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Http\Requests\User\StudentRequest;
use App\Models\Acl\Role;
use App\Utils\FlashMessageBuilder;
use App\Utils\Time\GradeAndYearUtil;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(MyStandardRequest $request){
        $this->dataForView['pageTitle'] = '学生档案管理';
        $schoolId = session('school.id');
        $this->dataForView['school_id'] = $schoolId;

        // 列出学校所有专业
        $this->dataForView['majors'] = (new MajorDao())->getMajorsBySchool($request->getSchoolId());
        $this->dataForView['grades'] = (new GradeDao())->getAllBySchool($request->getSchoolId());

        return view('teacher.profile.add_new_student', $this->dataForView);
    }

    public function suspend(StudentRequest $request){
        return 'student suspend';
    }

    public function stop(StudentRequest $request){
        return 'student stop';
    }

    public function reject(StudentRequest $request){
        return 'student reject';
    }

    public function update(StudentRequest $request){

        $userData = $request->get('user');
        $profileData = $request->get('profile');
        $majorData = $request->get('major');
        $gradeData = $request->get('grade');

        DB::beginTransaction();

        try{
            // 创建用户数据
            // 创建用户班级的关联
            // 创建用户的档案

            $userDao = new UserDao();
            $user = $userDao->importUser($userData['mobile'], $userData['name'],substr($profileData['id_number'], -6));
            $gradeUserDao = new GradeUserDao();

            $major = (new MajorDao())->getMajorById($majorData['id']);

            $gradeUserDao->create([
                'user_id'=>$user->id,
                'name'=>$user->name,
                'user_type' => $user->type,
                'school_id'=>$request->getSchoolId(),
                'campus_id'=>$major->campus_id,
                'institute_id'=>$major->institute_id,
                'department_id'=>$major->department_id,
                'major_id'=>$major->id,
                'grade_id'=>$gradeData['id'],
                'last_updated_by'=>$request->user()->id
            ]);

            $studentProfileDao = new StudentProfileDao();
            $profileData['user_id'] = $user->id;
            $profileData['uuid'] = Uuid::uuid4()->toString();
            $profileData['birthday'] = GradeAndYearUtil::IdNumberToBirthday($profileData['id_number'])->getData();
            $studentProfileDao->create($profileData);
            DB::commit();
            FlashMessageBuilder::Push($request,
                'success',
                '学生档案创建成功, 登陆密码为学生身份证的后六位: '.substr($profileData['id_number'], -6)
            );
        }
        catch (\Exception $exception){
            DB::rollBack();
            FlashMessageBuilder::Push($request,'danger',$exception->getMessage());
        }
        return redirect()->route('school_manager.school.students');
    }


    /**
     * 已注册用户
     * @param StudentRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function school_users(StudentRequest $request)
    {
        $dao = new GradeUserDao;
        $data = $dao->getBySchool(session('school.id'), [Role::REGISTERED_USER]);
        foreach ($data as $key => $val) {
            $val->user;
        }
        $this->dataForView['students'] = $data;
        $this->dataForView['pageTitle'] = '已注册用户管理';
        return view('teacher.users.users', $this->dataForView);

    }
}
