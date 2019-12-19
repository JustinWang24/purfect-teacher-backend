<?php


namespace App\Http\Controllers\Api\QrCode;

use App\Dao\Timetable\TimetableItemDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\QrCode\QrCodeRequest;
use App\Utils\JsonBuilder;
use Endroid\QrCode\QrCode;

class IndexController extends Controller
{

    /**
     * 生成二维码
     * @param QrCodeRequest $request
     * @return string
     * @throws \Endroid\QrCode\Exception\InvalidPathException
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

        $codeStr = base64_encode(json_encode(['school_id' => $school->id, 'api_token' => $user->api_token, 'time' => time()]));
        $qrCode = new QrCode($codeStr);
        $qrCode->setSize(200);
        $qrCode->setLogoPath(public_path('assets/img/logo.png'));
        $qrCode->setLogoSize(30, 30);
        $code = 'data:image/png;base64,' . base64_encode($qrCode->writeString());

        return JsonBuilder::Success(['code' => $code],'生成二维码');
    }

    /**
     * 生成上课补签二维码
     * @param QrCodeRequest $request
     */
    public function courseQrCode(QrCodeRequest $request)
    {
        $user = $request->user();
        $timeTableDao = new  TimetableItemDao;

        $data = $timeTableDao->getCurrentItemByUser($user);
        
        dd($data);
    }



}
