<?php


namespace App\Http\Controllers\Api\Cloud;

use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Dao\FacilityManage\FacilityDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Timetable\TimeSlotDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cloud\CloudRequest;
use App\Models\Schools\Facility;
use App\Models\Students\StudentProfile;
use App\Utils\JsonBuilder;
use Endroid\QrCode\QrCode;

class CloudController extends Controller
{

    /**
     * 根据设备码获取学校信息
     * @param CloudRequest $request
     * @return string
     */
    public function getSchoolInfo(CloudRequest $request)
    {
        $code = $request->get('code');

        $dao      = new FacilityDao;
        $facility = $dao->getFacilityByNumber($code);
        if (empty($facility)) {
            return JsonBuilder::Error('设备码错误,或设备已关闭');
        }
        /**
         * @var Facility $facility
         */
        $school = $facility->school;
        $data   = [
            'school' => [
                'name'  => $school->name,
                'motto' => $school->motto,
                'logo'  => [
                    'path' => $school->logo,
                    'size' => '',
                    'type' => ''
                ],
                'area'  => [
                    'video' => $school->video,
                    'size'  => '',
                    'type'  => '',
                ]
            ]
        ];

        return JsonBuilder::Success($data);
    }

    /**
     * 根据设备码获取班级信息
     * @param CloudRequest $request
     * @return string
     */
    public function getGradesInfo(CloudRequest $request)
    {
        $code = $request->get('code');

        $dao      = new FacilityDao;
        $facility = $dao->getFacilityByNumber($code);
        if (empty($facility)) {
            return JsonBuilder::Error('设备码错误,或设备已关闭');
        }

        /**
         * @var  Facility $facility
         */
        $room = $facility->room;

        $timeSlotDao = new TimeSlotDao;

        $item = $timeSlotDao->getItemByRoomForNow($room);

        if (empty($item)) {
            return JsonBuilder::Error('未找到班级', 1402);
        }

        $manager   = $item->grade->gradeManager;
        $gradeUser = $item->grade->gradeUser;
        $userIds   = $gradeUser->pluck('user_id');

        $studentProfileDao = new  StudentProfileDao;

        $man   = $studentProfileDao->getStudentGenderTotalByUserId($userIds, StudentProfile::GENDER_MAN);
        $woman = $studentProfileDao->getStudentGenderTotalByUserId($userIds, StudentProfile::GENDER_WOMAN);

        $data = [
            'class_name'    => $item->grade->name,
            'class_teacher' => $manager->name,
            'class_number'  => [
                'total' => $man + $woman,
                'man'   => $man,
                'woman' => $woman
            ],
            'class_img'     => [
                'class_img' => ''
            ]
        ];

        return JsonBuilder::Success($data);
    }

    /**
     * 根据设备码获取课程信息
     * @param CloudRequest $request
     * @return string
     */
    public function getCourseInfo(CloudRequest $request)
    {

        $code     = $request->get('code');
        $dao      = new FacilityDao;
        $facility = $dao->getFacilityByNumber($code);
        if (empty($facility)) {
            return JsonBuilder::Error('设备码错误,或设备已关闭');
        }
        /**
         * @var  Facility $facility
         */
        $room = $facility->room;

        $timeSlotDao = new TimeSlotDao;

        $items = $timeSlotDao->getTimeSlotByRoom($room);
        if (empty($items)) {
            return JsonBuilder::Error('未找到课程');
        }
        $data = [];
        foreach ($items as $key => $item) {
            $data[$key]['number']         = $item->timeslot->name;
            $data[$key]['course_time']    = $item->timeslot->from. ' - ' .$item->timeslot->to;
            $data[$key]['course_place']   = $item->room->name;
            foreach ($item->course->teachers as $teacher) {
                $data[$key]['course_teacher'] = $teacher->name;
            }
            $data[$key]['course_name']    = $item->course->name;
        }

        return JsonBuilder::Success($data);
    }


    /**
     * 生成签到二维码
     * @param CloudRequest $request
     * @return string
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     */
    public function getQrCode(CloudRequest $request)
    {

        $code     = $request->get('code');
        $dao      = new FacilityDao;
        $facility = $dao->getFacilityByNumber($code);
        if (empty($facility)) {
            return JsonBuilder::Error('设备码错误,或设备已关闭');
        }
        /**
         * @var  Facility $facility
         */
        $room = $facility->room;

        $timeSlotDao = new TimeSlotDao;

        $item = $timeSlotDao->getItemByRoomForNow($room);
        if (empty($item)) {
            return JsonBuilder::Error('未找到课程');
        }

        // 二维码生成规则学校ID, 班级ID, 课程ID
        $codeStr = 'cloud'. ',' .$item->schools_id. ',' .$item->grade_id. ',' .$item->course_id;
        $qrCode = new QrCode($codeStr);
        $qrCode->setSize(400);
        $qrCode->setLogoPath(public_path('assets/img/logo.png'));
        $qrCode->setLogoSize(60, 60);
        $code = 'data:image/png;base64,' . base64_encode($qrCode->writeString());

        return JsonBuilder::Success($code,'生成二维码');
    }

    /**
     * 签到统计
     * @param CloudRequest $request
     * @return string
     */
    public function getAttendanceStatistic(CloudRequest $request)
    {

        $code     = $request->get('code');
        $dao      = new FacilityDao;
        $facility = $dao->getFacilityByNumber($code);
        if (empty($facility)) {
            return JsonBuilder::Error('设备码错误,或设备已关闭');
        }
        /**
         * @var  Facility $facility
         */
        $room = $facility->room;

        $timeSlotDao = new TimeSlotDao;

        $item = $timeSlotDao->getItemByRoomForNow($room);
        if (empty($item)) {
            return JsonBuilder::Error('未找到课程');
        }

        $dao = new AttendancesDao;
        $data = $dao->getAttendanceById($item->id);

        return JsonBuilder::Success(['data' => $data]);
    }

}
