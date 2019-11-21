<?php

namespace App\Http\Requests\Textbook;

use App\Http\Requests\MyStandardRequest;

class TextbookRequest extends MyStandardRequest
{

    /**
     * 获取表单提交
     * @return mixed
     */
    public function getFormData() {
        return $this->get('textbook');
    }

    /**
     * 下载类型
     * @return mixed
     */
    public function getDownloadType() {
        return $this->get('download_type','csv');
    }

    /**
     * 获取教材ID，以逗号分割
     * @return array
     */
    public function getTextbookIdArr() {
        $textbookIds = $this->get('textbook_ids',null);
        return explode(',', $textbookIds);
    }

    public function getCourse(){
        return $this->get('course', null);
    }

    public function getTextbook(){
        return $this->get('textbook', null);
    }

    public function getCourses(){
        return $this->get('courses', []);
    }

    public function getQuery(){
        return $this->get('query', null);
    }

    public function getQueryScope(){
        return $this->get('scope', null);
    }
}
