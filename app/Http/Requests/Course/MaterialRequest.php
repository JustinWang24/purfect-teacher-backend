<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/1/20
 * Time: 4:33 PM
 */

namespace App\Http\Requests\Course;


use App\Http\Requests\MyStandardRequest;

class MaterialRequest extends MyStandardRequest
{
    /**
     * 获取 URL 中传入的教师的 ID
     * @return string
     */
    public function getTeacherId(){
        return $this->get('teacher');
    }


    public function getType() {
        return $this->get('type_id');
    }
}