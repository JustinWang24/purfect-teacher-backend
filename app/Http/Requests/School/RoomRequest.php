<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 12:54 PM
 */

namespace App\Http\Requests\School;
use App\Http\Requests\MyStandardRequest;

class RoomRequest extends MyStandardRequest
{

    /**
     * 获取上传文件
     * @return array|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|null
     */
    public function getFile() {
        return $this->file('file',null);
    }
}