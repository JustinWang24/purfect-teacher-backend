<?php

namespace App\Http\Requests\Textbook;

use App\Http\Requests\MyStandardRequest;

class TextbookRequest extends MyStandardRequest
{

    /**
     * 获取教材ID
     * @return mixed
     */
    public function getTextbookId() {
        return $this->get('id');
    }


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
}
