<?php

namespace App\Http\Controllers\Operator\Calendar;

use App\Dao\Calendar\CalendarDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\CalendarRequest;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 校历事件保存
     * @param CalendarRequest $request
     * @return
     */
    public function save(CalendarRequest $request)
    {
        $data = $request->get('event');
        $dao = new  CalendarDao;

        $result = $dao->createCalendarEvent($data);
        if ($request) {
            return cg;
        } else {
            return error;
        }
    }


    /**
     * 校历展示
     */
    public function index()
    {
        return view('school_manager.calendar.index', $this->dataForView);
    }
}
