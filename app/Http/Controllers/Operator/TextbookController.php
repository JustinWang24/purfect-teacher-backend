<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Textbook\TextbookDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Textbook\TextbookRequest;
use App\Utils\JsonBuilder;

class TextbookController extends Controller
{

    public function add(TextbookRequest $request) {

        if($request->isMethod('post')) {
//            $all = $request->post('textbook');
            $all = $request->all();
            $all['school_id'] = $request->getSchoolId();
            $textbookDao = new TextbookDao();
            $result = $textbookDao->saveTextbook($all);
            if($result) {
                return JsonBuilder::Success('创建成功');
            } else {
                return JsonBuilder::Error('创建失败');
            }

        }

        return view('school_manager.textbook.add', $this->dataForView);
    }
}
