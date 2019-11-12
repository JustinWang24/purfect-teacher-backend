<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\CampusRequest;
use App\Utils\FlashMessageBuilder;

class CampusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Func 
     * @param CampusRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function school(CampusRequest $request)
    {
        $dao = new SchoolDao($request->user());
        $school = $dao->getSchoolById($request->session()->get('school.id'));
        if($school){
            $this->dataForView['school'] = $school;
            return view('school_manager.school.view', $this->dataForView);
        }
        else{
            return redirect()->route('home');
        }
    }

    /**
     * 
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
     * 
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
     * 
     */
    public function add(){
        $this->dataForView['campus'] = new Campus();
        return view('school_manager.campus.add', $this->dataForView);
    }

    /**
     * 
     * @param CampusRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(CampusRequest $request){
        $this->dataForView['campus'] = Campus::find($request->uuid());
        return view('school_manager.campus.edit', $this->dataForView);
    }

    /**
     * 
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
