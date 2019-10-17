<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Http\Requests\School\CampusRequest;

class CampusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function school(CampusRequest $request){
        $dao = new SchoolDao($request->user());
        $school = $dao->getSchoolById($request->session()->get('school.id'));
        $this->dataForView['school'] = $school;
        return view('operator.school.view', $this->dataForView);
    }
}
