<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SchoolRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Models\School;

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
        $school = $dao->getSchoolByUuid($request->uuid());
        // 获取学校
        $request->session()->put('school.id',$school->id);
        $request->session()->put('school.uuid',$school->uuid);
        $request->session()->put('school.name',$school->name);
        return redirect()->route('operator.school.view');
    }

    /**
     * 加载用户表的编辑页面
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(SchoolRequest $request){
        $this->dataForView['school'] = new School();
        return view('admin.schools.add', $this->dataForView);
    }

    /**
     * 加载用户表的编辑页面
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        $this->dataForView['school'] = $dao->getSchoolByUuid($request->uuid());
        return view('admin.schools.edit', $this->dataForView);
    }

    /**
     * 加载用户表的编辑页面
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        $schoolData = $request->get('school');

        if(isset($schoolData['uuid'])){
            $result = $dao->updateSchool($schoolData);
        }
        else{
            $result = $dao->createSchool($schoolData);
        }

        if($result){
            // 更新成功
            $request->session()->flash('msg',['content' => '学校"'.$schoolData['name'].'"信息保存成功!', 'status' => 'success']);
        }
        else{
            $request->session()->flash('msg',['content' => '学校"'.$schoolData['name'].'"信息保存失败!', 'status' => 'danger']);
        }
        return redirect()->route('home');
    }
}
