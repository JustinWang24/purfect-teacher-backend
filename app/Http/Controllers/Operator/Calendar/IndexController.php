<?php

namespace App\Http\Controllers\Operator\Calendar;

use App\Dao\Calendar\CalendarDao;
use App\Dao\Schools\SchoolDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\CalendarRequest;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;
use Psy\Util\Json;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 校历事件保存
     * @param CalendarRequest $request
     * @return \Illuminate\Http\RedirectResponse|string
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
            $data['id'] = $result->id;
        }

        if($request->ajax()){
            return JsonBuilder::Success(['id'=>$data['id']]);
        }
        else{
            if ($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'保存成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'保存失败');
            }

            return redirect()->route('school_manger.school.calendar.index');
        }
    }

    /**
     * 删除校历事件
     * @param CalendarRequest $request
     * @return string
     */
    public function delete(CalendarRequest $request){
        $eventId = $request->get('event_id');
        $dao = new CalendarDao();
        return $dao->deleteEvent($eventId) ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * 校历展示
     * @param CalendarRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CalendarRequest $request)
    {
        $this->dataForView['pageTitle'] = '校历管理';
        $schoolId = $request->getSchoolId();

        $dao = new CalendarDao;
        $school = (new SchoolDao())->getSchoolById($request->getSchoolId());

        $data = $dao->getCalendarEvent($schoolId);

        $tags = [];
        foreach ($data as $datum) {
            if($datum->tag){
                foreach ($datum->tag as $item) {
                    $tags[] = $item;
                }
            }
        }

        $this->dataForView['events'] = $data;
        $this->dataForView['tags'] = $tags;
        $this->dataForView['currentDate'] = $request->get('cd', now()->format('Y-m-d'));

        $this->dataForView['school'] = $school;
        $this->dataForView['config'] = $school->configuration;
        $this->dataForView['weeks'] = $school->configuration->getAllWeeksOfTerm();

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
