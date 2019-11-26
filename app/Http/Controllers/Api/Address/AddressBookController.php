<?php

namespace App\Http\Controllers\Api\Address;

use App\Dao\Schools\GradeDao;
use App\Dao\Schools\OrganizationDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressBook\AddressBookRequest;
use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;

class AddressBookController extends Controller
{

    /**
     * 班级通讯录
     * @param AddressBookRequest $request
     * @return string
     */
    public function index(AddressBookRequest $request)
    {
        $gradeId = null;
        if ($request->has('grade_id')) {
            $gradeId = $request->get('grade_id');
        }

        if(!$gradeId){
            $uuid = $request->uuid();
            $userDao = new UserDao;
            $user = $userDao->getUserGradeByUuid($uuid);
            $gradeId = $user['gradeUser']['grade_id'];
        }

        if (!$gradeId) {
            return JsonBuilder::Error('未找到用户所在班级');
        }

        $gradeUserDao = new GradeUserDao;
        $data = $gradeUserDao->getGradeAddressBook($gradeId);
        return JsonBuilder::Success($data, '获取通讯录班级数据');
    }

    /**
     * 办公室通讯录
     * @param AddressBookRequest $request
     * @return string
     */
    public function official(AddressBookRequest $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();


        if(!$user){
            $uuid = $request->uuid();
            $userDao = new UserDao;
            $user = $userDao->getUserByUuid($uuid);
        }
        $schoolId = $user ? $user->getSchoolId() : null;

        if (!$schoolId) {
            // 检查是否提交了学校的 uuid
            $dao = new SchoolDao();
            $school = $dao->getSchoolByUuid($request->get('school'));
            $schoolId = $school->id ?? null;
        }

        if (!$schoolId) {
            return JsonBuilder::Error('未找到用户所在学校');
        }
        $dao = new OrganizationDao();
        $data = $dao->getBySchoolId($schoolId);
        $department_list = [];
        foreach ($data as $item){
            $department_list[] = [
                'name'=>$item->name,
                'tel'=>$item->phone,
                'address'=>$item->address,
            ];
        }
        return JsonBuilder::Success(['department_list'=>$department_list], '获取通讯录办公室数据');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function all_grades(Request $request){
        $schoolId = $request->get('school');
        $dao = new GradeDao();
        return JsonBuilder::Success(['grades'=>$dao->getAllBySchool($schoolId)]);
    }
}
