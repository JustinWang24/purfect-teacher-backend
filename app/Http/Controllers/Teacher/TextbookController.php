<?php

namespace App\Http\Controllers\Teacher;

use App\Utils\JsonBuilder;
use App\Dao\Textbook\TextbookDao;
use App\Http\Controllers\Controller;
use App\Dao\Textbook\DownloadOfficeDao;
use App\Http\Requests\Textbook\TextbookRequest;


class TextbookController extends Controller
{
     /**
     * 添加
     * @param TextbookRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(TextbookRequest $request) {
        return view('teacher.textbook.add', $this->dataForView);
    }



    /**
     * 编辑
     * @param TextbookRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TextbookRequest $request) {

        $textbookDao = new TextbookDao();
        $id = $request->getTextbookId();
        $info = $textbookDao->getTextbookById($id);
        $this->dataForView['textbook'] = $info;
        return view('teacher.textbook.edit', $this->dataForView);
    }


    /**
     * 保存
     * @param TextbookRequest $request
     * @return string
     */
    public function save(TextbookRequest $request) {
        $textbookDao = new TextbookDao();
        $all = $request->getFormData();
        if(!empty($all['id'])) {
            $result = $textbookDao->editById($all);
            if($result) {
                return JsonBuilder::Success('编辑成功');
            } else {
                return JsonBuilder::Error('编辑失败');
            }
        } else {
            $all['school_id'] = $request->getSchoolId();
            $result = $textbookDao->create($all);
            if($result->isSuccess()) {
                return JsonBuilder::Success('创建成功');
            } else {
                return JsonBuilder::Error($result->getMessage());
            }
        }

    }

    /**
     * 查看该专业所有教材的采购情况
     * @param TextbookRequest $request
     * @return string
     */
    public function loadMajorTextbook(TextbookRequest $request) {

        $schoolId = $request->getSchoolId();
        $textbookDao = new TextbookDao();
        $majorId = $request->getMajorId();
        $result = $textbookDao->getTextbooksByMajor($majorId,$schoolId);
        $data = ['major_textbook'=>$result];
        return JsonBuilder::Success($data);
    }



    /**
     * 查询以班级为单位的教材情况
     * @param TextbookRequest $request
     * @return string
     */
    public function loadGradeTextbook(TextbookRequest $request) {
        $gradeId = $request->getGradeId();
        $textbookDao = new TextbookDao();
        $result = $textbookDao->getTextbooksByGradeId($gradeId);

        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error($result->getMessage(),$result->getCode());
        }
    }


    /**
     * 班级教材下载
     * @param TextbookRequest $request
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function gradeTextbookDownload(TextbookRequest $request){

        // Todo  后续实现PDF下载

        $gradeId = $request->getGradeId();
        $type = $request->getDownloadType();
        $downloadOfficeDao = new DownloadOfficeDao();
        $result = $downloadOfficeDao->gradeDownload($gradeId, $type);
        if(!$result->isSuccess()) {
            return JsonBuilder::Error($result->getMessage(),$result->getCode());
        }
    }
}
