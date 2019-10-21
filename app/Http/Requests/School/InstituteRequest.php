<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 10:49 PM
 */

namespace App\Http\Requests\School;
use App\Http\Requests\MyStandardRequest;

class InstituteRequest extends MyStandardRequest
{
    public function getFormData(){
        return $this->get('institute');
    }
}