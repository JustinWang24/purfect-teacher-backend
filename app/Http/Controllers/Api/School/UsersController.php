<?php

namespace App\Http\Controllers\Api\School;

use App\BusinessLogic\QuickSearch\Factory;
use App\Dao\Courses\CourseTeacherDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Models\Acl\Role;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\Models\Users\GradeUser;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * 根据给定的姓名, 搜索老师的方法
     * @param Request $request
     * @return string
     */
    public function search_by_name(Request $request){
        // 获取需要搜索的教师姓名
        $name = $request->get('query');
        // 获取限定的学校的 ID
        $schoolId = $request->get('school');
        // 限定所选择的专业
        $majorsId = $request->get('majors');

        $dao = new TeacherProfileDao();
        // 搜索过程, 先简单处理, 在数据量比较小的情况下, 直接搜索 teacher profiles 表, 而不考虑 major 来缩小范围
        $result = $dao->searchTeacherByNameSimple($name, $schoolId, []); // Todo: 未来需要附加此条件, 限定查找专业
        return JsonBuilder::Success(['teachers'=>$result]);
    }

    /**
     * 根据给定的课程加载授课的老师
     * @param Request $request
     * @return string
     */
    public function load_course_teachers(Request $request){
        $dao = new CourseTeacherDao();
        return JsonBuilder::Success(['teachers'=>$dao->getTeachersByCourse($request->get('course'))]);
    }

    /**
     * 快速定位用户的 action
     * @param Request $request
     * @return string
     */
    public function quick_search_users(Request $request){
        $logic = Factory::GetInstance($request);

        $data = [];

        if($logic){
            $users = $logic->getUsers();
            $facilities = $logic->getFacilities();
            if(!empty($facilities)){
                foreach ($facilities as $facility) {
                    $item = [
                        'id'=>$facility->id,
                        'value'=>$facility->name,
                        'scope'=>$request->get('scope'),
                    ];
                    $nextAction = $logic->getNextAction($facility);
                    if(!empty($nextAction)){
                        $item['nextAction'] = $nextAction;
                    }
                    $data[] = $item;
                }
            }
            if($users){
                foreach ($users as $gradeUser) {
                    $nextAction = $logic->getNextAction($gradeUser);
                    $item = null;
                    if($gradeUser instanceof GradeUser){
                        /**
                         * @var GradeUser $gradeUser
                         */
                        if($gradeUser->user_type === Role::VERIFIED_USER_STUDENT){
                            $item = [
                                'id'=>$gradeUser->user_id,
                                'value'=>$gradeUser->name . ' - ' . $gradeUser->grade->name . ' ' . $gradeUser->major->name,
                                'scope'=>'user',
                                'uuid'=>$gradeUser->user->uuid,
                                'nextAction'=>route('verified_student.profile.edit',['uuid'=>$gradeUser->user->uuid]),
                            ];
                        }
                        else{
                            $item = [
                                'id'=>$gradeUser->user_id,
                                'value'=>$gradeUser->name,
                                'scope'=>'user',
                                'uuid'=>$gradeUser->user->uuid,
                                'nextAction'=>route('school_manager.teachers.edit-profile',['uuid'=>$gradeUser->user->uuid]),
                            ];
                        }
                    }
                    elseif($gradeUser instanceof RegistrationInformatics){
                        // 这里是对报名学生的搜索结果
                        /**
                         * @var RegistrationInformatics $gradeUser
                         */
                        $item = [
                            'id'=>$gradeUser->user_id,
                            'value'=>$gradeUser->name. ': '.$gradeUser->getStatusText().', '.$gradeUser->plan->year.'年 '.$gradeUser->plan->title,
                            'scope'=>'registration',
                        ];

                        if(!empty($nextAction)){
                            $item['nextAction'] = $nextAction;
                        }
                    }

                    $data[] = $item;
                }
            }
        }
        return JsonBuilder::Success($data);
    }

    /**
     * 获取用户名字, 根据给定的学校和用户 ID
     * @param Request $request
     * @return string
     */
    public function get_user_name(Request $request){
        $schoolId = $request->get('school');
        $userId = $request->get('user');
        $dao = new GradeUserDao();
        $exist = $dao->isUserInSchool($userId, $schoolId);
        if($exist){
            return JsonBuilder::Success(['name'=>$exist->name]);
        }else{
            return JsonBuilder::Error('查找的用户不存在');
        }
    }

    /**
     * 获取指定学校的所有老师
     * 提交 school_id, 返回所有的教师 id 和名字
     * @param Request $request
     * @return string
     */
    public function teachers(Request $request){
        $schoolId = $request->get('school_id');
        $dao = new UserDao();
        $teachers = $dao->getTeachersBySchool($schoolId, true);
        return JsonBuilder::Success(['teachers'=>$teachers]);
    }
}
