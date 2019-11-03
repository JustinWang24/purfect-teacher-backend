<?php

namespace App\Http\Controllers\Api\Recruitment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlansController extends Controller
{

    public function load_plans(Request $request){
        $schoolId = $request->get('school');
        $year = $request->has('year') ? $request->get('year') : date('Y');

        
    }
}
