<?php

namespace App\Http\Controllers\Operator;

use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Schools\CampusDao;
use App\Utils\Files\HtmlToCsv;
use App\Dao\Users\GradeUserDao;
use App\Dao\Textbook\TextbookDao;
use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Textbook\DownloadOfficeDao;
use App\BusinessLogic\UsersListPage\Factory;
use App\Http\Requests\Textbook\TextbookRequest;

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


    public function grade(TextbookRequest $request) {
        $logic = Factory::GetLogic($request);

        return view('teacher.textbook.user.list',
            array_merge($this->dataForView, $logic->getUsers()));

    }


    public function users(TextbookRequest $request) {

        $userId = $request->get('user_id');
        $year = $request->get('year');
        if(is_null($year)) {
            $year = Carbon::now()->year;
        }
        $dao = new GradeUserDao();
        $gradeInfo = $dao->getUserInfoByUserId($userId);
        $textBookDao = new TextbookDao();
        $info = $textBookDao->userTextbook($gradeInfo, $year);
        $this->dataForView['gradeUser'] = $gradeInfo;
        $this->dataForView['textbooks'] = $info;
//        dd($info);
        return view('teacher.textbook.user.edit',$this->dataForView);
    }


    public function getTextbook(TextbookRequest $request) {
        $userId = $request->get('user_id');
        $textbookId = $request->getTextbookId();
        $dao = new TextbookDao();
        $data = ['user_id'=>$userId, 'textbook_id'=>$textbookId];
        $re = $dao->getTextbook($data);
        if($re) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'领取成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'领取失败');
            }

            return redirect()->route('school_manager.textbook.users',['user_id'=>$userId]);

    }

}
