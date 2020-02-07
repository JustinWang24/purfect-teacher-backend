<?php

namespace App\Http\Controllers\Api\OA;

use App\Dao\Schools\GradeManagerDao;
use App\Dao\Schools\GradeResourceDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\Acl\Role;
use App\Models\Schools\GradeResource;
use App\Utils\JsonBuilder;

class GradeManageController extends Controller
{

    /**
     * 获取班级
     * @param MyStandardRequest $request
     * @return string
     */
    public function index(MyStandardRequest $request)
    {
        $teacher = $request->user();

        $dao = new GradeManagerDao;
        $grades = $dao->getAllGradesByAdviserId($teacher->id);
        $data = [];
        foreach ($grades as $key => $val) {
             $data[$key]['grade_id'] = $val->grade->id;
             $data[$key]['name'] = $val->grade->name;

             $data[$key]['image'] = [];
             foreach ($val->grade->gradeResource as $k => $v) {
                $data[$key]['image'][$k]['image_id'] = $v->id;
                $data[$key]['image'][$k]['path'] = $v->path;
             }
        }

        return JsonBuilder::Success($data);
    }

    /**
     * 上传班级风采
     * @param MyStandardRequest $request
     * @return string
     */
    public function uploadGradeResource(MyStandardRequest $request)
    {
         $gradeId = $request->get('grade_id');
         $file = $request->file('file');
         $data['grade_id'] = $gradeId;
         $data['name'] = $file->getClientOriginalName();
         $data['type'] = $file->extension();
         $data['size'] = getFileSize($file->getSize());
         $data['path'] = GradeResource::gradeResourceUploadPathToUrl($file->store(GradeResource::DEFAULT_UPLOAD_PATH_PREFIX));

         $dao = new GradeResourceDao;
         $result = $dao->create($data);
         if($result) {
             return JsonBuilder::Success('上传成功');
         } else {
             return JsonBuilder::Error('上传失败');
         }
    }

    /**
     * 刪除班級风采
     * @param MyStandardRequest $request
     * @return string
     */
    public function delGradeResource(MyStandardRequest $request)
    {
        $id = $request->get('image_id');
        $dao  = new GradeResourceDao;
        $result = $dao->delete($id);
        if ($result) {
            return JsonBuilder::Success('删除成功');
        } else {
            return JsonBuilder::Error('删除失败');
        }
    }

    /**
     * 班级列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function gradesList(MyStandardRequest $request)
    {
        $teacher = $request->user();
        $dao = new GradeManagerDao;
        $grades = $dao->getAllGradesByAdviserId($teacher->id);
        $data = [];
        foreach ($grades as $key => $val) {
            $data[$key]['grade_id'] = $val->grade->id;
            $data[$key]['name'] = $val->grade->name;
        }

        return JsonBuilder::Success($data);
    }

    /**
     * 学生列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function studentList(MyStandardRequest $request)
    {
        $gradeId = $request->get('grade_id');

        $dao = new GradeUserDao;
        $data = $dao->paginateUserByGrade($gradeId, Role::VERIFIED_USER_STUDENT);
        $result = [];
        foreach ($data as $key => $val) {
            $result[$key]['student_id'] = $val->user_id;
            $result[$key]['name'] = $val->name;
        }
        return JsonBuilder::Success($result);
    }

    /**
     * 学生详情信息
     * @param MyStandardRequest $request
     * @return string
     */
    public function studentInfo(MyStandardRequest $request)
    {
        $studentId = $request->get('student_id');

        $dao = new  UserDao;
        $user = $dao->getUserById($studentId);
        $profile = $user->profile;
        $gradeUser = $user->gradeUser;
        $grade     = $user->gradeUser->grade;
        $monitor   = $user->monitor;
        $group     = $user->group;
        $data = [
            'grade_id'       => $grade->id,
            'student_id'     => $user->id,
            'name'           => $user->name,  // 姓名
            'id_number'      => $profile->id_number,
            'gender'         => $profile->gender, // 男女
            'birthday'       => $profile->birthday, // 出生年月日
            'nation_name'    => $profile->nation_name, // 民族
            'political_name' => $profile->political_name, // 政治面貌
            'source_place'   => $profile->source_place,  // 生源地
            'country'        => $profile->country, // 籍贯
            'contact_number' => $profile->contact_number, // 联系电话
            'qq'             => $profile->qq,
            'wx'             => $profile->wx,
            'parent_name'    => $profile->parent_name,  // 家长姓名
            'parent_mobile'  => $profile->parent_mobile, // 家长联系电话
            'state'          => $profile->state, //省市名称
            'city'           => $profile->city, // 市名称
            'area'           => $profile->area, //地区名称
            'address_line'   => $profile->address_line, // 详情地址
            'email'          => $user->email,
            'school_year'    => '4', // 学制
            'education'      => '', // 学历
            'institute'      => $gradeUser->institute->name,
            'major'          => $gradeUser->major->name,
            'year'           => $grade->year.'级',
            'monitor'        => $monitor == null ? false : true, // 班长
            'group'          => $group == null ? false : true,  // 团支书
        ];

        return JsonBuilder::Success($data);
    }

    /**
     * 修改学生信息
     * @param MyStandardRequest $request
     * @return string
     */
    public function updateStudentInfo(MyStandardRequest $request)
    {
        $studentId = $request->get('student_id');
        $data = $request->get('data');
        $monitor = $request->get('monitor');
        $group = $request->get('group');

        $dao = new StudentProfileDao;
        $gradeManagerDao = new GradeManagerDao;
        $gradeResult = $gradeManagerDao->updateGradeManger($monitor['grade_id'], $monitor);
        $studentResult =  $dao->updateStudentProfile($studentId, $data);
        $groupResult = $gradeManagerDao->updateGradeManger($group['grade_id'], $group);
        if ($gradeResult || $studentResult || $groupResult) {
            return JsonBuilder::Success('修改成功');
        } else {
            return JsonBuilder::Error('修改失败');
        }
    }

}
