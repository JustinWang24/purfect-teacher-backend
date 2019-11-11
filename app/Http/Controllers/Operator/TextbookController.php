<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Textbook\DownloadOfficeDao;
use App\Dao\Textbook\TextbookDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Textbook\TextbookRequest;
use App\Utils\JsonBuilder;

class TextbookController extends Controller
{


    /**
     * 列表
     * @param TextbookRequest $request
     * @return string
     */
    public function list(TextbookRequest $request) {
        $schoolId = $request->getSchoolId();
        $textbookDao = new TextbookDao();
        $list = $textbookDao->getTextbookListBySchoolId($schoolId);
        foreach ($list as $key => $val) {
            $list[$key]['type'] = $val['type_text'];
        }

        $data['textbook']=$list;
        return JsonBuilder::Success($data);
    }



    /**
     * 通过校区查询教材的购买情况
     * @param TextbookRequest $request
     * @return string
     */
    public function loadCampusTextbook(TextbookRequest $request) {
        $campusId = $request->getCampusId();
        $textbookDao = new TextbookDao();
        $result = $textbookDao->getCampusTextbook($campusId);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }

    }

    /**
     * 校区教材采购下载
     * @return string
     * @param TextbookRequest $request
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function campusTextbookDownload(TextbookRequest $request) {

         // Todo  后续实现PDF下载

        $campusId = $request->getCampusId();
        $type = $request->getDownloadType();
        $downloadOfficeDao = new DownloadOfficeDao();
        $result = $downloadOfficeDao->campusDownload($campusId, $type);
        if(!$result->isSuccess()) {
            return JsonBuilder::Error($result->getMessage(),$result->getCode());
        }
    }

}
