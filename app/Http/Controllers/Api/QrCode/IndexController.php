<?php


namespace App\Http\Controllers\Api\QrCode;

use App\Dao\Schools\SchoolDao;
use App\Dao\Users\UserDao;
use App\Models\Users\UserCodeRecord;
use App\Utils\JsonBuilder;
use Endroid\QrCode\QrCode;
use App\Http\Controllers\Controller;
use App\Dao\Users\UserCodeRecordDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Http\Requests\QrCode\QrCodeRequest;
use Endroid\QrCode\Exception\InvalidPathException;

class IndexController extends Controller
{

    /**
     * 生成学生 教师端 首页 二维码
     * @param QrCodeRequest $request
     * @return string
     * @throws InvalidPathException
     */
    public function generate(QrCodeRequest $request)
    {
        $school = $request->getAppSchool();
        if (empty($school)) {
            return JsonBuilder::Error('未找到学校');
        }
        $user  = $request->user();
        if (empty($user)) {
            return JsonBuilder::Error('未找到用户');
        }
        // 生成规则 : 识别标识+学校ID+用户ID+时间戳
        $codeStr = base64_encode(json_encode(['app' => UserCodeRecord::IDENTIFICATION_APP, 'school_id' => $school->id, 'user_id' => $user->id, 'time' => time()]));
        $code = $this->generateQrCode($codeStr);
        if (!$code) {
            return  JsonBuilder::Error('生成二维码失败');
        }

        return JsonBuilder::Success(['code' => $code],'生成二维码');
    }

    /**
     * 生成上课补签二维码.
     * @param QrCodeRequest $request
     * @return string
     * @throws InvalidPathException
     */
    public function courseQrCode(QrCodeRequest $request)
    {
        $user = $request->user();
        $timeTableDao = new  TimetableItemDao;
        $item = $timeTableDao->getCurrentItemByUser($user);
        if (is_null($item)) {
            return JsonBuilder::Error('未找到您正在上的课');
        }
            
        $codeStr = base64_encode(json_encode(['app'=> 'supplement',  'itme_id' => $item->id, 'year' => $item->year, 'term' => $item->term]));
        $code = $this->generateQrCode($codeStr);
        if (!$code) {
            return  JsonBuilder::Error('生成二维码失败');
        }

        return JsonBuilder::Success(['code' => $code],'上课补签二维码');
    }

    /**
     * 生成二维码
     * @param $codeStr
     * @return string
     * @throws InvalidPathException
     */
    public function generateQrCode($codeStr)
    {
        $qrCode = new QrCode($codeStr);
        $qrCode->setSize(200);
        $qrCode->setLogoPath(public_path('assets/img/logo.png'));
        $qrCode->setLogoSize(30, 30);
        $code = 'data:image/png;base64,' . base64_encode($qrCode->writeString());
        if (strlen($code) < 1) {
            return  false;
        } else {
            return  $code;
        }
    }


    /**
     * 创建二维码使用记录
     * @param QrCodeRequest $request
     * @return string
     */
    public function createRecord(QrCodeRequest $request) {
        $user = $request->user();
        $data = $request->get('code');
        $data['user_id'] = $user->id;
        $data['school_id'] = $user->getSchoolId();
        $dao = new UserCodeRecordDao();
        $result = $dao->create($data);
        if($result) {
            return JsonBuilder::Success(['id'=>$result->id],'创建成功');
        } else {
            return JsonBuilder::Error('创建失败');
        }
    }

    /**
     * 扫码获取个人信息
     * @param QrCodeRequest $request
     * @return string
     */
    public function information(QrCodeRequest $request)
    {
        $code = base64_decode($request->get('code'));
        $data = json_decode($code);
        if (!$data) {
            return JsonBuilder::Error('不能识别此二维码');
        }

        if (!$data->user_id) {
            return JsonBuilder::Error('二维码错误');
        }

        $dao = new UserDao;
        $user = $dao->getUserById($data->user_id);
        // 头像, 姓名 , 班级
        if ($user->isTeacher()) {
            return JsonBuilder::Error('不可以扫老师的二维码');
        }

        $schoolId = $user->getSchoolId();
        $schoolDao = new SchoolDao;
        $school = $schoolDao->getSchoolById($schoolId);

        $data = [
            'name' => $user->getName(),
            'avatar' => $user->profile->avatar,
            'grade_name' => $user->gradeUser->grade->name,
            'school_name' => $school->name,
        ];

        return JsonBuilder::Success($data);
    }

}
