<?php
namespace App\Http\Controllers\Teacher;

use App\Dao\Teachers\ExamDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ExamRequest;
use App\Utils\JsonBuilder;
use Carbon\Carbon;

class ExamController extends Controller
{

    /**
     * 考试列表
     */
    public function index()
    {

    }

    public function create(ExamRequest $request)
    {
        $all = $request->all();

        $all['school_id'] = $request->session()->get('school.id');
        $dt = Carbon::parse($all['from']);
        $all['year'] = $dt->year;
        $all['month'] = $dt->month;
        $all['week'] = $dt->week;
        $all['day'] = $dt->day;
        $all['exam_time'] = $dt->toDateString();
        $all['from'] = $dt->toTimeString();
        $all['to'] = Carbon::parse($all['to'])->toTimeString();
        $examDao = new ExamDao();
        $re = $examDao->create($all);
        if($re->id)
        {
            return JsonBuilder::Success(['id'=>$re->id],'添加成功');
        }
        else
        {
            return JsonBuilder::Error('添加失败');

        }
    }
}