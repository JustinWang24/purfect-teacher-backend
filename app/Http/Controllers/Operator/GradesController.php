<?php

namespace App\Http\Controllers\Operator;

use App\Http\Requests\School\GradeRequest;
use App\Http\Controllers\Controller;
use App\BusinessLogic\UsersListPage\Factory;
use App\Dao\Schools\MajorDao;
use App\Models\Schools\Grade;
use App\Dao\Schools\GradeDao;
use App\Models\Schools\GradeManager;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;

class GradesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(GradeRequest $request){
        $dao = new MajorDao($request->user());
        $this->dataForView['parent'] = $dao->getMajorById($request->uuid());
        $this->dataForView['grade'] = new Grade();
        return view('school_manager.grade.add', $this->dataForView);
    }

    public function edit(GradeRequest $request){
        $gradeDao = new GradeDao($request->user());
        $grade = $gradeDao->getGradeById($request->uuid());
        $this->dataForView['grade'] = $grade;
        $this->dataForView['parent'] = $grade->major;

        return view('school_manager.grade.edit', $this->dataForView);
    }

    /**
     * 保存班级
     * @param GradeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(GradeRequest $request){
        $majorData = $request->getFormData();
        $dao = new GradeDao($request->user());
        $uuid = $majorData['major_id'];

        if(isset($majorData['id'])){
            // 更新专业的操作
            if($dao->updateGrade($majorData)){
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $majorData['name'].'班级已经修改成功');
            }else{
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['name'].'班级修改失败, 请重新试一下');
            }
        }else{
            // 新增专业的操作
            if($dao->createGrade($majorData)){
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $majorData['name'].'班级已经创建成功');
            }else{
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['name'].'班级创建失败, 请重新试一下');
            }
        }
        return redirect()->route('school_manager.major.grades',['uuid'=>$uuid, 'by'=>'major']);
    }

    public function set_adviser(GradeRequest $request){
        if($request->isMethod('POST')){
            $adviserData = $request->getAdviserForm();
            $result = (new GradeDao())->setAdviser($adviserData);

            return $result->isSuccess() ? JsonBuilder::Success():JsonBuilder::Error($result->getMessage());
        }
        elseif ($request->isMethod('GET')){
            $this->dataForView['pageTitle'] = '设置班主任';
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
            return view('school_manager.grade.set_adviser',$this->dataForView);
        }
    }
}
