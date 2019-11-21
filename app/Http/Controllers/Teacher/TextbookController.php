<?php

namespace App\Http\Controllers\Teacher;

use App\Dao\Courses\CourseTextbookDao;
use App\Dao\Schools\MajorDao;
use App\Utils\Files\HtmlToCsv;
use App\Utils\JsonBuilder;
use App\Dao\Textbook\TextbookDao;
use App\Http\Controllers\Controller;
use App\Dao\Textbook\DownloadOfficeDao;
use App\Http\Requests\Textbook\TextbookRequest;

class TextbookController extends Controller
{
    public function manager(TextbookRequest $request){
        $this->dataForView['pageTitle'] = '教材管理';
        $this->dataForView['user'] = $request->user();
        // 如果是学校级别的, 则加载所有院系的

        // 如果是普通教师角色, 则加载自己所教授的课程的

        return view('teacher.textbook.manager', $this->dataForView);
    }

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
                return JsonBuilder::Success(['textbook'=>$result->getData()]);
            } else {
                return JsonBuilder::Error('编辑失败');
            }
        } else {
            $all['school_id'] = $request->getSchoolId();
            $result = $textbookDao->create($all);
            if($result->isSuccess()) {
                $data = ['textbook'=>$result->getData()];
                return JsonBuilder::Success($data,'创建成功');
            } else {
                return JsonBuilder::Error($result->getMessage());
            }
        }
    }

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
     * 列表
     * @param TextbookRequest $request
     * @return string
     */
    public function list_paginate(TextbookRequest $request) {
        $schoolId = $request->getSchoolId();
        $pageNumber = $request->getPageNumber();
        $pageSize = $request->getPageSize();

        $textbookDao = new TextbookDao();
        $list = $textbookDao->getTextbookListPaginateBySchoolId($schoolId, $pageNumber, $pageSize);
        return JsonBuilder::Success($list);
    }

    /**
     * 教材更新课程的接口
     * @param TextbookRequest $request
     * @return string
     */
    public function update_related_courses(TextbookRequest $request){
        $book = $request->getTextbook();
        $courses = $request->getCourses();

        $textbookDao = new TextbookDao();
        $updated = $textbookDao->updateRelatedCourses($book, $courses, $request->getSchoolId());
        return $updated ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * 课程绑定教材
     * @param TextbookRequest $request
     * @return string
     */
    public function courseBindingTextbook(TextbookRequest $request) {
        $courseId = $request->getCourseId();
        $schoolId = $request->getSchoolId();
        $textbookIdArr = $request->getTextbookIdArr();
        $courseTextbookDao = new CourseTextbookDao();
        $result = $courseTextbookDao->createCourseTextbook($courseId, $schoolId, $textbookIdArr);

        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
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

        $majorDao = new MajorDao();
        $major = $majorDao->getMajorById($majorId);

        $this->dataForView['major'] = $major;
        $this->dataForView['major_textbook'] = $result;

        if($request->isDownloadRequest()){
            $path = HtmlToCsv::Convert(
                'teacher.textbook.elements.table_by_major',
                $this->dataForView
            );
            if($path){
                return response()->download($path,$major->name.'教材汇总表.xls');
            }
        }

        return view('teacher.textbook.to_csv_by_major',$this->dataForView);
    }

    /**
     * @param TextbookRequest $request
     * @return string
     */
    public function search(TextbookRequest $request){
        $schoolId = $request->getSchoolId();
        $scope = $request->getQueryScope();
        $query = $request->getQuery();
        if(empty(trim($query))){
            return JsonBuilder::Success(['books'=>[]]);
        }

        $textbookDao = new TextbookDao();
        $result = $textbookDao->searchByName($query, $schoolId, $scope);
        return JsonBuilder::Success(['books'=>$result]);
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
            $data = ['textbook'=>$result->getData()];
            return JsonBuilder::Success($data);
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
