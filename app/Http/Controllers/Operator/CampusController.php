<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\BuildingDao;
use App\Dao\Schools\RoomDao;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Http\Requests\School\CampusRequest;
use App\Dao\Schools\InstituteDao;
use App\Dao\Schools\CampusDao;
use App\Utils\FlashMessageBuilder;
use App\Models\Schools\Campus;
use Illuminate\Support\Facades\Route;

class CampusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 查看学校的校区列表
     * @param CampusRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function school(CampusRequest $request){
        $dao = new SchoolDao($request->user());
        $school = $dao->getSchoolById($request->session()->get('school.id'));
        if($school){
            $this->dataForView['school'] = $school;
            $config = $school->configuration;
            if(is_null($config)){
                $config = $dao->createDefaultConfig($school);
            }
            $this->dataForView['config'] = $config;
            return view('school_manager.school.view', $this->dataForView);
        }
        else{
            return redirect()->route('home');
        }
    }

    /**
     * 查看校区包含的学院
     * @param CampusRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function institutes(CampusRequest $request){
        $campusDao = new CampusDao($request->user());
        $instituteDao = new InstituteDao($request->user());

        $campus = $campusDao->getCampusById($request->uuid(), $request->session()->get('school.id'));
        if($campus){
            $this->dataForView['campus'] = $campus;
            $this->dataForView['institutes'] = $instituteDao->getByCampus($campus);
            return view('school_manager.school.institutes', $this->dataForView);
        }
        else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'您操作的学院不存在');
            return redirect()->route('home');
        }
    }

    /**
     * 列出学院的某一类建筑物
     * @param CampusRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buildings(CampusRequest $request){
        $dao = new CampusDao($request->user());
        $campus = $dao->getCampusById($request->uuid());
        if($campus){
            $this->dataForView['parent'] = $campus;
            $buildingDao = new BuildingDao($request->user());
            $this->dataForView['buildings'] = $buildingDao->getBuildingsByType(intval($request->get('type')), $campus);
            return view('school_manager.school.buildings', $this->dataForView);
        }
    }

    /**
     * 加载添加校区的表单
     */
    public function add(){
        $this->dataForView['campus'] = new Campus();
        return view('school_manager.campus.add', $this->dataForView);
    }

    /**
     * 加载添加校区的表单
     * @param CampusRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(CampusRequest $request){
        $campusDao = new CampusDao($request->user());
        $this->dataForView['campus'] = $campusDao->getCampusById($request->uuid());
        return view('school_manager.campus.edit', $this->dataForView);
    }

    /**
     * 保存校区的方法
     * @param CampusRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CampusRequest $request){
        $campusData = $request->get('campus');
        $campusData['school_id'] = $request->session()->get('school.id');
        $campusDao = new CampusDao($request->user());

        if(isset($campusData['id'])){
            $result = $campusDao->updateCampus($campusData);
        }
        else{
            $result = $campusDao->createCampus($campusData);
        }

        if($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$campusData['name'].'校区保存成功');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'无法保存校区'.$campusData['name']);
        }
        return redirect()->route('school_manager.school.view');
    }
}
