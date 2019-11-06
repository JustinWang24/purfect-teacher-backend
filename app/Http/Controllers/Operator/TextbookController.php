<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Textbook\TextbookDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Textbook\TextbookRequest;
use App\Utils\JsonBuilder;

class TextbookController extends Controller
{

    /**
     * 添加
     * @param TextbookRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(TextbookRequest $request) {
        return view('school_manager.textbook.add', $this->dataForView);
    }



    /**
     * 编辑
     * @param TextbookRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TextbookRequest $request) {

        $textbookDao = new TextbookDao();
        $id = $request->get('id');
        $info = $textbookDao->getTextbookById($id);
        $this->dataForView['textbook'] = $info;
        return view('school_manager.textbook.edit', $this->dataForView);
    }


    /**
     * 保存
     * @param TextbookRequest $request
     * @return string
     */
    public function save(TextbookRequest $request) {
        $textbookDao = new TextbookDao();
        $all = $request->get('textbook');;
        if(!empty($all['id'])) {
            $id = $all['id'];
            unset($all['id']);
            $result = $textbookDao->editById($id,$all);
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


    //查看该专业所以教材的采购情况
    public function loadMajorTextbook(TextbookRequest $request) {

        $majorId = $request->get('major_id','2971');
        $textbookDao = new TextbookDao();
        $result = $textbookDao->getTextbooksByMajor($majorId);

        return JsonBuilder::Success($result);
    }



}
