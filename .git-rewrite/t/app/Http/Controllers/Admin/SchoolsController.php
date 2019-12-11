<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SchoolRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Models\School;
use App\Utils\FlashMessageBuilder;

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
            // 保存成功
            FlashMessageBuilder::Push($request,FlashMessageBuilder::SUCCESS, '学校"'.$schoolData['name'].'"信息保存成功!');
        }
        else{
            FlashMessageBuilder::Push($request,FlashMessageBuilder::DANGER, '学校"'.$schoolData['name'].'"信息保存失败!');
        }
        return redirect()->route('home');
    }
}
