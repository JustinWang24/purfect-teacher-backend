<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\DepartmentDao;
use App\Dao\Schools\MajorDao;
use App\Http\Requests\School\MajorRequest;
use App\Http\Controllers\Controller;
use App\Models\Schools\Major;
use App\Utils\FlashMessageBuilder;

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

    }

    public function add(MajorRequest $request){
        $dao = new DepartmentDao($request->user());
        $this->dataForView['department'] = $dao->getDepartmentById($request->uuid());
        $this->dataForView['major'] = new Major();
        return view('school_manager.major.add', $this->dataForView);
    }

    public function edit(MajorRequest $request){
        $majorDao = new MajorDao($request->user());
        $major = $majorDao->getMajorById($request->uuid());
        if($major){
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
        $uuid = $request->uuid();

        if(isset($majorData['id'])){
            $uuid = $majorData['department_id'];
            // 更新专业的操作
            if($dao->updateMajor($majorData)){
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $majorData['name'].'专业已经修改成功');
            }else{
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['name'].'专业修改失败, 请重新试一下');
            }
        }else{
            // 新增专业的操作
            if($dao->createMajor($majorData)){
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, $majorData['name'].'专业已经创建成功');
            }else{
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, $majorData['name'].'专业创建失败, 请重新试一下');
            }
        }
        return redirect()->route('school_manager.department.majors',['uuid'=>$uuid,'by'=>'department']);
    }
}
