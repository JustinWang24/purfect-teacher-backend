<?php

namespace App\Http\Controllers\Api\School;

use App\BusinessLogic\QuickSearch\Factory;
use App\Dao\Courses\CourseTeacherDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Models\Users\GradeUser;
use App\User;
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
        $result = $dao->searchTeacherByNameSimple($name, $schoolId, $majorsId);
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

    public function quick_search_users(Request $request){
        $logic = Factory::GetInstance($request);

        $data = [];

        if($logic){
            $users = $logic->getUsers();
            $facilities = $logic->getFacilities();
            if($facilities){
                foreach ($facilities as $facility) {
                    $data[] = [
                        'id'=>$facility->id,
                        'value'=>$facility->name,
                        'scope'=>$request->get('scope')
                    ];
                }
            }
            if($users){
                foreach ($users as $user) {
                    /**
                     * @var GradeUser $user
                     */
                    $data[] = [
                        'id'=>$user->id,
                        'value'=>$user->name . ' - ' . $user->grade->name . ' ' . $user->major->name,
                        'scope'=>'user'
                    ];
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
}
