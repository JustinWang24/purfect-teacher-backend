<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/12/19
 * Time: 8:44 PM
 */

namespace App\Http\Controllers\H5\Teacher;

use App\Dao\OA\VisitorDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function visitors(Request $request){
        $user = $request->user('api');
        $dao = new VisitorDao();
        $this->dataForView['pageTitle'] = '今日预约';
        $this->dataForView['api_token'] = $request->get('api_token');
        $this->dataForView['teacher'] = $user;
        $schoolId = $user->getSchoolId();
        $this->dataForView['schoolId'] = $schoolId;
        $this->dataForView['visitors'] = $dao->getTodayVisitorsBySchoolIdForApp($schoolId);
        return view('h5_apps.teacher.management.visitors', $this->dataForView);
    }
}