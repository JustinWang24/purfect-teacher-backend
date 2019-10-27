<?php

namespace App\Http\Controllers\Api\Timetable;

use App\BusinessLogic\TimetableLogic\SpecialItemsLoadLogic;
use App\BusinessLogic\TimetableLogic\TimetableBuilderLogic;
use App\BusinessLogic\TimetableLogic\TimetableItemBeforeCreate;
use App\BusinessLogic\TimetableLogic\TimetableItemBeforeUpdate;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Users\UserDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimetableItemsController extends Controller
{
    protected $userDao;
    public function __construct()
    {
        $this->userDao = new UserDao();
    }

    /**
     * 检查提交的数据是否可以插入到数据中
     * @param Request $request
     * @return string
     */
    public function can_be_inserted(Request $request){
        $logic = new TimetableItemBeforeCreate($request);
        $logic->check();
        if($logic->checked){
            return JsonBuilder::Success();
        }
        return JsonBuilder::Error($logic->errorMessage);
    }

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
        $user = $this->userDao->getUserByUuid($request->get('user'));
        $result = $dao->deleteItem($request->get('id'), $user);
        return $result ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function publish(Request $request){
        $dao = new TimetableItemDao();
        $user = $this->userDao->getUserByUuid($request->get('user'));
        $result = $dao->publishItem($request->get('id'), $user);
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
        $weekType = intval($request->get('weekType')); // 指示位: 是否为单双周

        $logic = new TimetableBuilderLogic($schoolId,$gradeId, $weekType, $term, $year);
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

    /**
     * 创建新的课程表调课项
     * @param Request $request
     * @return string
     */
    public function create_special_case(Request $request){
        $specialCase = $request->get('specialCase');
        $dao = new TimetableItemDao();
        $item = $dao->getItemById($specialCase['to_replace']);

        $user = $this->userDao->getUserByUuid($request->get('user'));

        if($user && $user->isSchoolAdminOrAbove()){
            $result = $dao->createSpecialCase($specialCase, $item, $user);
            return $result ? JsonBuilder::Success(['grade_id'=>$result->grade_id]) : JsonBuilder::Error();
        }
        else{
            return JsonBuilder::Error('没有权限进行此操作');
        }
    }

    public function load_special_cases(Request $request){
        $ids = $request->get('ids');

        $logic = new SpecialItemsLoadLogic($ids);

        return JsonBuilder::Success(['specials'=>$logic->build()]);
    }
}
