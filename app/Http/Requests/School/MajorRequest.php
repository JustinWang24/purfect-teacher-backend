<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 9:57 AM
 */

namespace App\Http\Requests\School;
use App\Http\Requests\MyStandardRequest;

class MajorRequest extends MyStandardRequest
{
    public function getFormData(){
        return $this->get('major');
    }
}