<?php
namespace App\Http\Controllers\Operator;

use App\Dao\Schools\CampusDao;
use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Dao\FacilityManage\FacilityDao;
use App\Http\Requests\FacilityManage\MonitoringRequest;

class FacilityController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function list(MonitoringRequest $request) {


        $schoolId = $request->getSchoolId();
        $facilityDao = new FacilityDao();
        $map = ['school_id' => $schoolId];
        $result = $facilityDao->getFacilityPage($map);


        $this->dataForView['facility'] = $result;

        return view('school_manager.facility.list', $this->dataForView);

    }


    public function add(MonitoringRequest $request) {

        if($request->isMethod('post')) {
            $all = $request->post('facility');
            $all['school_id'] = $request->getSchoolId();
            $facilityDao = new FacilityDao();
            $result = $facilityDao->save($all);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'保存成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'保存失败');
            }
            return redirect()->route('school_manager.facility.list');

        }
        $user = $request->user();
        $field = ['id', 'name'];
        $campusDao = new CampusDao($user);
        $schoolId = $request->getSchoolId();
        $campus = $campusDao->getCampusesBySchool($schoolId,$field);
        $this->dataForView['campus'] = $campus;
        return view('school_manager.facility.add', $this->dataForView);

    }


    public function edit(MonitoringRequest $request) {
        $facilityDao = new FacilityDao();
        if($request->isMethod('post')) {
            $all = $request->post('facility');
            $result = $facilityDao->save($all);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'编辑成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
            }
            return redirect()->route('school_manager.facility.list');
        }

        $user = $request->user();
        $schoolId = $request->getSchoolId();
        $id = $request->get('id');
        $result = $facilityDao->facilityInfoDispose($id,$user,$schoolId);
        $this->dataForView['facility'] = $result['facility'];
        $this->dataForView['campus'] = $result['campus'];
        return view('school_manager.facility.edit', $this->dataForView);
    }


    public function delete(MonitoringRequest $request) {
        $id = $request->get('id');
        $facilityDao = new FacilityDao();
        $result = $facilityDao->delete($id);
        if($result) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'删除成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'删除失败');
        }
        return redirect()->route('school_manager.facility.list');
    }


}
