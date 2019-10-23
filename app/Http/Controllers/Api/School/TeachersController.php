<?php

namespace App\Http\Controllers\Api\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeachersController extends Controller
{

    public function search_by_name(Request $request){
        // 获取需要搜索的教师姓名
        $name = $request->get('query');
        // 获取限定的学校的 ID
        $schoolId = $request->get('school');
        // 限定所选择的专业
        $majorsId = $request->get('majors');
    }
}
