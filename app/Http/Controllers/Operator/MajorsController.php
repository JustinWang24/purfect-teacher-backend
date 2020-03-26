<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\DepartmentDao;
use App\Dao\Schools\MajorDao;
use App\Http\Requests\School\MajorRequest;
use App\Http\Controllers\Controller;
use App\Models\Schools\Major;
use App\Utils\FlashMessageBuilder;
use App\BusinessLogic\UsersListPage\Factory;

class MajorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function grades(MajorRequest $request){
        $dao = new MajorDao($request->user());

        $major = $dao->getMajorById($request->uuid());
        $this->dataForView['parent'] = $major;
        $this->dataForView['grades'] = $major->grades;

        return view('school_manager.school.grades', $this->dataForView);
    }

    public function users(MajorRequest $request){
        $logic = Factory::GetLogic($request);
        $this->dataForView['parent'] = $logic->getParentModel();
        $this->dataForView['returnPath'] = $logic->getReturnPath();
        // 给 Pagination 用
        $this->dataForView['appendedParams'] = $logic->getAppendedParams();
        return view($logic->getViewPath(), array_merge($this->dataForView, $logic->getUsers()));
    }

    public function add(MajorRequest $request){
        $dao = new DepartmentDao($request->user());
        $major = new Major();
        $this->dataForView['department'] = $dao->getDepartmentById($request->uuid());
        $this->dataForView['major'] = $major;
        $this->dataForView['all_type'] = $major->AllTypes();
        return view('school_manager.major.add', $this->dataForView);
    }

    public function edit(MajorRequest $request){
        $majorDao = new MajorDao($request->user());
        $major = $majorDao->getMajorById($request->uuid());
        if($major){
            $this->dataForView['all_type'] = $major->AllTypes();
            $this->dataForView['department'] = $major->department;
            $this->dataForView['major'] = $major;

            return view('school_manager.major.edit', $this->dataForView);
        }else{
            // major 没取到
        }
    }

    /**
     * @param MajorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MajorRequest $request){
        $majorData = $request->getFormData();
        $dao = new MajorDao($request->user());
        $uuid = $majorData['department_id'];
        $result = $dao->getMajorByCategoryCode($majorData['category_code'], $majorData['school_id']);

        if ($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['category_code'].'专业代码已经用了, 请重新添加');
            return redirect()->route('school_manager.department.majors',['uuid'=>$majorData['department_id'],'by'=>'department']);
        } else {
            if(isset($majorData['id'])){
                $major = $dao->getMajorById($majorData['id']);
                // 更新专业的操作
                if($dao->updateMajor($majorData)){
                    FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $majorData['name'].'专业已经修改成功');
                }else{
                    FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['name'].'专业修改失败, 请重新试一下');
                }
            }else{
                // 新增专业的操作
                $major = $dao->createMajor($majorData);
                if($major){
                    FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $majorData['name'].'专业已经创建成功');
                }else{
                    FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['name'].'专业创建失败, 请重新试一下');
                }
            }
            return redirect()->route('school_manager.department.majors',['uuid'=>$major->department_id,'by'=>'department']);
        }



    }
}
