<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 2:52 PM
 */

namespace App\Http\Requests\School;
use App\Http\Requests\MyStandardRequest;

class GradeRequest extends MyStandardRequest
{
    public function getFormData(){
        return $this->get('grade');
    }
    public function getAdviserForm(){
        return $this->get('adviser');
    }
}