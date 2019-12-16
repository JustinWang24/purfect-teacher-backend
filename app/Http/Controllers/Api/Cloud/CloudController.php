<?php


namespace App\Http\Controllers\Api\Cloud;

use App\Dao\FacilityManage\FacilityDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cloud\CloudRequest;
use App\Models\School;
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

        $dao = new FacilityDao;
        $facility = $dao->getFacilityByNumber($code);
        if (empty($facility)) {
            return JsonBuilder::Error('设备码错误,或设备已关闭',1401);
        }
        /**
         * @var $school School
         */
        $school = $facility->school;
        $data = [
            'school' => [
                'name' => $school->name,
                'motto' => $school->motto,
                'logo' => [
                    'path' => $school->logo,
                    'size' => '',
                    'type' => ''
                ],
                'area' => [
                    'video' => $school->video,
                    'size'  => '',
                    'type'  => '',
                ]
            ]
        ];

        return  JsonBuilder::Success($data);
    }

    /**
     * 根据设备码获取班级信息
     * @param CloudRequest $request
     * @return string
     */
    public function getGradesInfo(CloudRequest $request)
    {
        $code = $request->get('code');

        $dao = new FacilityDao;
        $facility = $dao->getFacilityByNumber($code);
        if (empty($facility)) {
            return JsonBuilder::Error('设备码错误,或设备已关闭',1401);
        }

        $data = [
            'class_name' => '',
            'class_teacher' => '',
            'class_number' => '',
            'class_img' => [
                'class_img' => ''
            ]
        ];

        return JsonBuilder::Success($data);
    }

    /**
     * 根据设备码获取课程信息
     * @param CloudRequest $request
     */
    public function getCourseInfo(CloudRequest $request)
    {

    }


    /**
     * 生成签到二维码
     * @param CloudRequest $request
     * @return string
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     */
    public function getQrCode(CloudRequest $request)
    {
        // 二维码生成规则学校ID, 班级ID, 课程ID
        $codeStr = 'cloud'. ',' .$timeTable->schools_id. ',' .$timeTable->grade_id. ',' .$timeTable->id;
        $qrCode = new QrCode($codeStr);
        $qrCode->setSize(200);
        $qrCode->setLogoPath(public_path('assets/img/logo.png'));
        $qrCode->setLogoSize(30, 30);
        $code = 'data:image/png;base64,' . base64_encode($qrCode->writeString());

        return JsonBuilder::Success($code,'生成二维码');
    }


    /**
     * 签到统计
     * @param CloudRequest $request
     */
    public function getAttendanceStatistic(CloudRequest $request)
    {

    }

}
