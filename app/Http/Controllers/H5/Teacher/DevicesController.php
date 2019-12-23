<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/12/19
 * Time: 4:07 PM
 */

namespace App\Http\Controllers\H5\Teacher;
use App\Dao\FacilityManage\FacilityDao;
use App\Http\Controllers\Controller;
use App\Models\Schools\Facility;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function devices(Request $request){
        $user = $request->user('api');
        $this->dataForView['teacher'] = $user;
        $this->dataForView['api_token'] = $request->get('api_token');
        $location = $request->get('location', Facility::LOCATION_INDOOR);
        $devices = (new FacilityDao())->getFacilityByLocation($user->getSchoolId(), $location);
        $this->dataForView['devices'] = $devices;
        $this->dataForView['location'] = $location;
        return view('h5_apps.teacher.management.devices', $this->dataForView);
    }
}