<?php

namespace App\Http\Controllers\Api\Timetable;

use App\BusinessLogic\TimetableLogic\TimetableBuilderLogic;
use App\BusinessLogic\TimetableLogic\TimetableItemBeforeCreate;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimetableItemsController extends Controller
{
    /**
     * 保存课程表项目的接口
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $logic = new TimetableItemBeforeCreate($request);
        $item = $logic->check()->create();
        if($item){
            return JsonBuilder::Success(['id'=>$item->id]);
        }else{
            return JsonBuilder::Error();
        }
    }

    public function load(Request $request){
        // Todo: 查询的必要提交是班级 id, 年和学期
        $gradeId = $request->get('grade');
        $year = $request->get('year');
        $term = $request->get('term');
        $schoolId = $request->get('school');

        $logic = new TimetableBuilderLogic($schoolId,$gradeId, $term, $year);
        return JsonBuilder::Success(['timetable'=>$logic->build()]);
    }
}
