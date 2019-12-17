<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\DepartmentDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\MajorDao;
use App\Dao\Schools\OrganizationDao;
use App\Dao\Schools\RoomDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Dao\Users\UserOrganizationDao;
use App\Http\Requests\SchoolRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Models\Acl\Role;
use App\Models\School;
use App\Utils\FlashMessageBuilder;
use App\Dao\Schools\InstituteDao;
use App\Utils\JsonBuilder;
use Illuminate\Support\Facades\DB;

class SchoolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 管理员选择某个学校作为操作对象
     * @param SchoolRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enter(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        // 获取学校
        $school = $dao->getSchoolByUuid($request->uuid());
        $school->savedInSession($request);
        return redirect()->route('school_manager.school.view');
    }

    /**
     * 更新学校的配置信息
     * @param SchoolRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function config_update(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        $school = $dao->getSchoolByUuid($request->uuid());
        // 要比较学校中每个系的相同的配置项目, 如果学校的要求高于系的要求, 那么就要覆盖系的. 如果低于系的要求, 那么就保留
        if($school){
            $dao->updateConfiguration(
                $school,
                $request->getConfiguration(),
                $request->getElectiveCourseAvailableTerm(1),
                $request->getElectiveCourseAvailableTerm(2),
                $request->getTermStart(),
                $request->getSummerStart(),
                $request->getWinterStart()
            );
            FlashMessageBuilder::Push($request, 'success','配置已更新');
        }
        else{
            FlashMessageBuilder::Push($request, 'danger','无法获取学校数据');
        }

        if($request->get('redirectTo',null)){
            return redirect($request->get('redirectTo',null));
        }
        return redirect()->route('school_manager.school.view');
    }

    public function institutes(SchoolRequest $request){
        $instituteDao = new InstituteDao($request->user());
        $this->dataForView['institutes'] = $instituteDao->getBySchool(session('school.id'));
        $this->dataForView['pageTitle'] = '学院管理';
        return view('school_manager.school.institutes', $this->dataForView);
    }

    public function departments(SchoolRequest $request){
        $dao = new DepartmentDao($request->user());
        $this->dataForView['departments'] = $dao->getBySchool(session('school.id'));
        $this->dataForView['pageTitle'] = '院系管理';
        return view('school_manager.school.departments', $this->dataForView);
    }

    public function majors(SchoolRequest $request){
        $dao = new MajorDao($request->user());
        $this->dataForView['majors'] = $dao->getBySchool(session('school.id'));
        $this->dataForView['pageTitle'] = '专业管理';
        return view('school_manager.school.majors', $this->dataForView);
    }

    public function grades(SchoolRequest $request){
        $dao = new GradeDao($request->user());
        $this->dataForView['grades'] = $dao->getBySchool(session('school.id'));
        $this->dataForView['pageTitle'] = '班级管理';
        return view('school_manager.school.grades', $this->dataForView);
    }

    public function teachers(SchoolRequest $request){
        $dao = new GradeUserDao($request->user());
        $this->dataForView['employees'] = $dao->getBySchool(session('school.id'), Role::GetTeacherUserTypes());
        $this->dataForView['pageTitle'] = '教职工管理';
        return view('teacher.users.teachers', $this->dataForView);
    }

    public function students(SchoolRequest $request){
        $dao = new GradeUserDao($request->user());
        $this->dataForView['students'] = $dao->getBySchool(session('school.id'),Role::GetStudentUserTypes());
        $this->dataForView['pageTitle'] = '学生管理';
        return view('teacher.users.students', $this->dataForView);
    }

    public function rooms(SchoolRequest $request){
        $dao = new RoomDao($request->user());
        $this->dataForView['rooms'] = $dao->getRoomsPaginate([['school_id','=',session('school.id')]]);
        $this->dataForView['pageTitle'] = '物业管理';
        return view('school_manager.school.rooms', $this->dataForView);
    }

    /**
     * 加载学校的组织机构
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function organization(SchoolRequest $request){
        $this->dataForView['pageTitle'] = '组织架构';
        $dao = new OrganizationDao();
        $this->dataForView['root'] = $dao->getRoot($request->getSchoolId());
        $this->dataForView['level'] = $dao->getTotalLevel($request->getSchoolId());
        return view('school_manager.school.organization', $this->dataForView);
    }

    /**
     * @param SchoolRequest $request
     * @return string
     */
    public function load_parent(SchoolRequest $request){
        $level = intval($request->get('level')) - 1;
        $orgs = [];
        if($level > 0){
            $dao = new OrganizationDao();
            $orgs = $dao->loadByLevel($level, $request->getSchoolId());
        }
        return JsonBuilder::Success(['parents'=>$orgs]);
    }

    /**
     * 获取某个级别或者指定的父级单位的下级单位集合
     * @param SchoolRequest $request
     * @return string
     */
    public function load_children(SchoolRequest $request){
        $level = intval($request->get('level'));
        $parentId = $request->get('parent_id', null);
        $dao = new OrganizationDao();
        $orgs = [];
        if($parentId){
            $orgs = $dao->getById($parentId)->branch;
        }
        else{
            $orgs = $dao->loadByLevel($level, $request->getSchoolId());
        }
        return JsonBuilder::Success(['orgs'=>$orgs]);
    }

    /**
     * 保存组织结构
     * @param SchoolRequest $request
     * @return string
     */
    public function save_organization(SchoolRequest $request){
        $form = $request->get('form');
        $form['school_id'] = $request->getSchoolId();
        $dao = new OrganizationDao();
        if(isset($form['id']) && !empty($form['id'])){
            $id = $form['id'];
            unset($form['id']);
            if(isset($form['members'])) {
                unset($form['members']);
            }
            if(isset($form['updated_at'])) {
                unset($form['updated_at']);
            }
            $org = $dao->update($form, $id);
        }
        else{
            $org = $dao->create($form);
        }
        return JsonBuilder::Success(['org'=>$org]);
    }

    /**
     * 保存组织结构
     * @param SchoolRequest $request
     * @return string
     */
    public function load_organization(SchoolRequest $request){
        $id = $request->get('organization_id');
        $dao = new OrganizationDao();
        $org = $dao->getById($id);
        return JsonBuilder::Success(['organization'=>$org,'members'=>$org->members]);
    }


    /**
     * 删除组织结构及人员
     * @param SchoolRequest $request
     * @return string
     * @throws \Exception
     */
    public function delete_organization(SchoolRequest $request){
        $id = $request->get('organization_id');
        $dao = new OrganizationDao();

        try {
            DB::beginTransaction();
            $dao->deleteOrganization($id);
            DB::commit();
            return JsonBuilder::Success();
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return JsonBuilder::Error($msg);
        }

    }

    /**
     * 保存机构成员
     * @param SchoolRequest $request
     * @return string
     */
    public function add_member(SchoolRequest $request){
        $dao = new UserOrganizationDao();
        $member = $request->get('member');
        if(empty($member['id'])){
            $result = $dao->create($member);
            if($result->isSuccess()){
                return JsonBuilder::Success(['id'=>$result->getData()->id]);
            }
            else{
                return JsonBuilder::Error($result->getMessage());
            }
        }
        else{
            // 更新
            $id = $member['id'];
            unset($member['id']);
            $result = $dao->update($id, $member);
            if($result){
                return JsonBuilder::Success(['id'=>$id]);
            }
            else{
                return JsonBuilder::Error();
            }
        }
    }

    /**
     * @param SchoolRequest $request
     * @return string
     */
    public function remove_member(SchoolRequest $request){
        $dao = new UserOrganizationDao();
        if($dao->delete($request->get('id'))){
            return JsonBuilder::Success();
        }
        else{
            return JsonBuilder::Error();
        }
    }
}
