<?php

namespace App\Http\Controllers\Api\Timetable;

use App\BusinessLogic\TimetableLogic\TimetableBuilderLogic;
use App\BusinessLogic\TimetableLogic\TimetableItemBeforeCreate;
use App\BusinessLogic\TimetableLogic\TimetableItemBeforeUpdate;
use App\Dao\Timetable\TimetableItemDao;
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
        }
        // Todo: 创建失败需要指明原因
        return JsonBuilder::Error();
    }

    /**
     * 更新已经存在的课程表项目
     * @param Request $request
     * @return string
     */
    public function update(Request $request){
        $logic = new TimetableItemBeforeUpdate($request);
        $updated = $logic->check()->update();

        if($updated){
            return JsonBuilder::Success();
        }
        // Todo: 更新失败需要指明原因
        return JsonBuilder::Error();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function delete(Request $request){
        $dao = new TimetableItemDao();
        $result = $dao->deleteItem($request->get('id'));
        return $result ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * 克隆项目
     * @param Request $request
     * @return string
     */
    public function clone_item(Request $request){
        $dao = new TimetableItemDao();
        $result = $dao->cloneItem($request->get('item'));
        return $result ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * 加载整个课程表
     * @param Request $request
     * @return string
     */
    public function load(Request $request){
        // Todo: 查询的必要提交是班级 id, 年和学期
        $gradeId = $request->get('grade');
        $year = $request->get('year');
        $term = $request->get('term');
        $schoolId = $request->get('school');

        $logic = new TimetableBuilderLogic($schoolId,$gradeId, $term, $year);
        return JsonBuilder::Success(['timetable'=>$logic->build()]);
    }

    /**
     * 加载单个课程表中的某项
     * @param Request $request
     * @return string
     */
    public function load_item(Request $request){
        $dao = new TimetableItemDao();
        $item = $dao->getItemById($request->get('id'));
        return JsonBuilder::Success(['timetableItem'=>$item??'']);
    }
}
