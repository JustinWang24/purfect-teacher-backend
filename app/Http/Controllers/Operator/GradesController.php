<?php

namespace App\Http\Controllers\Operator;

use App\Http\Requests\School\GradeRequest;
use App\Http\Controllers\Controller;
use App\BusinessLogic\UsersListPage\Factory;
use App\Dao\Schools\MajorDao;
use App\Models\Schools\Grade;
use App\Dao\Schools\GradeDao;
use App\Utils\FlashMessageBuilder;

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
}
