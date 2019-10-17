<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SchoolRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;

class SchoolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 加载用户表的编辑页面
     * @param SchoolRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        $this->dataForView['school'] = $dao->getSchoolByIdOrUuid($request->uuid());
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
        if($dao->updateSchool($schoolData)){
            // 更新成功
            $request->session()->flash('msg',['content' => '学校信息更新成功!', 'status' => 'success']);
        }
        else{
            $request->session()->flash('msg',['content' => '学校信息更新失败!', 'status' => 'danger']);
        }
        return redirect()->route('home');
    }
}
