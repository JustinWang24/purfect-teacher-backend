<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Schools\MajorDao;
use App\Http\Controllers\Controller;
use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;

class MajorsController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function load_by_school(Request $request){
        $majorDao = new MajorDao(new User());
        return JsonBuilder::Success(['majors'=>$majorDao->getMajorsBySchool($request->get('id'))]);
    }
}
