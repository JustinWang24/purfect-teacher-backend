<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/14
 * Time: 下午2:17
 */

namespace App\Http\Controllers\Api\School;


use App\Dao\Schools\OrganizationDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\UserDao;
use App\Dao\Users\UserOrganizationDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\Teachers\Teacher;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\RelatedGroups;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    /**
     * 获取组织和人员
     * @param MyStandardRequest $request
     * @return string
     */
    public function getOrganization(MyStandardRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $parentId = $request->get('parent_id');
        $keyword = $request->get('keyword');
        $type = $request->get('type', 1); // type 1:搜索部门 2:搜索人员
        $dao = new OrganizationDao();
        // 部门数据
        $organ = [];
        if($type == 1) {
            if(!empty($keyword)) {
                $result = $dao->getOrganByName($schoolId, $keyword);
                $parentId = 0;
            } else {
                $result = $dao->getByParentId($schoolId, $parentId);
            }
            $organ = $this->organDataDispose($result);
            $userOrganDao = new UserOrganizationDao();
            $members = $userOrganDao->getOrganUserByOrganId($schoolId, $parentId);
        } else {

            if(!empty($keyword)) {
                $userDao = new UserDao();
                $members = $userDao->getTeachersBySchool($schoolId, true, $keyword);
            } else {
                $result = $dao->getByParentId($schoolId, $parentId);

                $organ = $this->organDataDispose($result);
                $userOrganDao = new UserOrganizationDao();
                $members = $userOrganDao->getOrganUserByOrganId($schoolId, $parentId);
            }
        }

        $userIds  = array_column($members->toArray(),'id');
        $teacherDao = new TeacherProfileDao();
        $profile = $teacherDao->getTeacherProfileByUserIds($userIds)->toArray();
        $title = array_column($profile,'title', 'user_id');
        $avatar = array_column($profile, 'avatar', 'user_id');
        foreach ($members as $key => $item) {
            $item->title = $title[$item->id] ?? '';
            $item->avatar = $avatar[$item->id] ?? '';
        }

        $data = [
            'organ' => $organ,
            'members' => $members
        ];
        return JsonBuilder::Success($data);
    }


    /**
     * 数据处理
     * @param $data
     * @return array
     */
    public function organDataDispose($data) {
        $organ = [];
        foreach ($data as $key => $item) {
            $organ[$key]['id'] =$item->id;
            $organ[$key]['name'] =$item->name;
            $organ[$key]['level'] =$item->level;
            $branches = $item->branch;
            $organ[$key]['status'] = true;
            if(count($branches) == 0) {
                $organ[$key]['status'] = false;
            }
        }

        return $organ;
    }

    public function load_by_roles(Request $request){
        // 获取上传的用户信息
        /**
         * @var User $user
         */
        $user = $request->user('api');
        $schoolId = $request->has('school') ? $request->get('school') : $user->getSchoolId();
        $relatedGroups = new RelatedGroups($user, $schoolId);

        // 获取用户的所有可能的角色
        if($user->isSchoolManager()){
            // 那就返回所有的: 部门加 年级/班级
        }
        elseif($user->isEmployee() || $user->isTeacher()){
            // 返回权限之内的部门 加 年级班级
        }
        elseif($user->isStudent()){
            // 如果是学生
        }
        $roles = $request->get('roles');
        if(empty($roles)){
//            $duties = Teacher::getTeacherAllDuties($user->id);
//            dd($duties);
        }
        return JsonBuilder::Success($relatedGroups->toArray());
    }
}