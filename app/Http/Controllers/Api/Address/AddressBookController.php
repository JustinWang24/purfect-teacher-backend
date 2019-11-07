<?php

namespace App\Http\Controllers\Api\Address;

use App\Dao\Section\SectionDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressBook\AddressBookRequest;
use App\Utils\JsonBuilder;

class AddressBookController extends Controller
{

    /**
     * 班级通讯录
     * @param AddressBookRequest $request
     * @return string
     */
    public function index(AddressBookRequest $request)
    {
        $uuid = $request->uuid();
        $userDao = new UserDao;
        $user = $userDao->getUserGradeByUuid($uuid);

        if (!$user['gradeUser']['grade_id']) {
            return JsonBuilder::Error('未找到用户所在班级');
        }

        $gradeUserDao = new GradeUserDao;
        $data = $gradeUserDao->getGradeAddressBook($user['gradeUser']['grade_id']);
        return JsonBuilder::Success($data, '获取通讯录班级数据');
    }

    /**
     * 办公室通讯录
     * @param AddressBookRequest $request
     * @return string
     */
    public function official(AddressBookRequest $request)
    {
        $uuid = $request->uuid();
        $userDao = new UserDao;
        $user = $userDao->getUserGradeByUuid($uuid);

        if (!$user['gradeUser']['school_id']) {
            return JsonBuilder::Error('未找到用户所在学校');
        }

        $sectionDao = new SectionDao;
        $data = $sectionDao->getSectionMobileBySchoolId($user['gradeUser']['school_id']);

        return JsonBuilder::Success($data, '获取通讯录办公室数据');
    }


}
