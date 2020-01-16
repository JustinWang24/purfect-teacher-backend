<?php


namespace App\Http\Controllers\Api\Evaluate;


use App\Utils\JsonBuilder;
use App\Dao\Evaluate\EvaluateDao;
use App\Models\Evaluate\Evaluate;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Evaluate\EvaluateTeacherRecordDao;
use App\Http\Requests\Evaluate\EvaluateTeacherRecordRequest;

class EvaluateTeacherRecordController extends Controller
{
    /**
     * 学生提交评教
     * @param EvaluateTeacherRecordRequest $request
     * @return string
     */
    public function save_evaluate(EvaluateTeacherRecordRequest $request) {
        $data = $request->getRecordData();

        if(empty($data['item_id']) || empty($data['week'])) {
            return JsonBuilder::Error('缺少参数');
        }

        $student = $data['student'];
        $record = $data['record'];
        $itemId = $data['item_id'];
        unset($data['record']);
        unset($data['student']);
        unset($data['item_id']);

        $itemDao = new TimetableItemDao();
        $item = $itemDao->getItemById($itemId);
        $data['year'] = $item->year;
        $data['type'] = $item->term;
        $data['weekday_index'] = $item->weekday_index;
        $data['user_id'] = $item->teacher_id;
        $data['time_slot_id'] = $item->time_slot_id;
        $dao = new EvaluateTeacherRecordDao();

        $result = $dao->create($data, $record, $student);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            $data = $result->getData();
            return JsonBuilder::Success($data, $msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }


    /**
     * 模版数据
     * @param EvaluateTeacherRecordRequest $request
     * @return string
     */
    public function template(EvaluateTeacherRecordRequest $request) {
        $schoolId = $request->user()->getSchoolId();
        $dao = new EvaluateDao();
        $list = $dao->getEvaluate($schoolId,Evaluate::TYPE_TEACHER, 'asc');
        return JsonBuilder::Success($list);
    }


}
