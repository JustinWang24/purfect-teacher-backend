<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\CampusDao;
use App\Dao\Textbook\DownloadOfficeDao;
use App\Dao\Textbook\TextbookDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Textbook\TextbookRequest;
use App\Utils\JsonBuilder;
use App\Utils\Files\HtmlToCsv;

class TextbookController extends Controller
{

    /**
     * 通过校区查询教材的购买情况
     * @param TextbookRequest $request
     * @return string
     */
    public function loadCampusTextbook(TextbookRequest $request) {
        $campusId = $request->getCampusId();

        $campusDao = new CampusDao();
        $campus = $campusDao->getCampusById($campusId);
        $this->dataForView['campus'] = $campus;

        $textbookDao = new TextbookDao();
        $result = $textbookDao->getCampusTextbook($campusId);
        $this->dataForView['campus_textbook'] = $result->getData();

        if($request->isDownloadRequest()){
            $path = HtmlToCsv::Convert(
                'teacher.textbook.elements.table_by_campus',
                $this->dataForView
            );
            if($path){
                return response()->download($path,$campus->name.'教材汇总表.xls');
            }
        }

        return view('teacher.textbook.to_csv_by_campus',$this->dataForView);

//        if($result->isSuccess()) {
//            $data = ['campus_textbook'=>$result->getData()];
//            return JsonBuilder::Success($data);
//        } else {
//            return JsonBuilder::Error($result->getMessage());
//        }

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
