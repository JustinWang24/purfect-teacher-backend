<?php

namespace App\Http\Controllers\Operator\Calendar;

use App\Dao\Calendar\CalendarDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\CalendarRequest;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 校历事件保存
     * @param CalendarRequest $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function save(CalendarRequest $request)
    {
        $data = $request->get('event');
        $data['school_id']  = $request->getSchoolId();
        $dao = new  CalendarDao;
        if ($data['id']) {
            $result = $dao->updateCalendarEvent($data);
        } else {
            $result = $dao->createCalendarEvent($data);
        }

        if ($request) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'保存成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'保存失败');
        }

        return redirect()->route('school_manger.school.calendar.index');
    }

    /**
     * 校历展示
     * @param CalendarRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CalendarRequest $request)
    {
        $schoolId = $request->getSchoolId();

        $dao = new CalendarDao;

        $data = $dao->getCalendarEvent($schoolId);

        $this->dataForView['data'] = $data;

        return view('school_manager.calendar.index', $this->dataForView);
    }

    /**
     * 校历事件详情
     * @param CalendarRequest $request
     * @return string
     */
    public function getEventDetails(CalendarRequest $request)
    {
        $id = $request->get('id');

        $dao = new CalendarDao;

        $data = $dao->getEventById($id);

        return JsonBuilder::Success($data, '获取校历事件详情');
    }


}
