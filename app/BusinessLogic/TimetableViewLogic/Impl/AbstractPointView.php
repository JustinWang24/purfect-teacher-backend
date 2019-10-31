<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 6:47 PM
 */

namespace App\BusinessLogic\TimetableViewLogic\Impl;
use App\BusinessLogic\TimetableViewLogic\Contracts\ITimetableBuilder;
use App\Dao\Timetable\TimeSlotDao;
use App\Dao\Timetable\TimetableItemDao;
use Illuminate\Http\Request;
use Carbon\Carbon;

abstract class AbstractPointView implements ITimetableBuilder
{
    protected $schoolId;
    protected $weekType;
    protected $term;
    protected $year;
    protected $timetableItemDao;
    protected $timeSlotDao;

    /**
     * 查询的必要提交是班级 id, 年和学期
     * TimetableBuilderLogic constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->year = $request->get('year') ?? Carbon::now()->year;
        $this->term = $request->get('term');
        $this->schoolId = $request->get('school');
        $this->weekType = intval($request->get('weekType')); // 指示位: 是否为单双周

        $this->timeSlotDao = new TimeSlotDao();
        // 找到所有的和学习相关的时间段
        $forStudyingSlots = $this->timeSlotDao->getAllStudyTimeSlots($this->schoolId);
        // 构建课程表项的 DAO
        $this->timetableItemDao = new TimetableItemDao($forStudyingSlots);
    }
}