<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 8:59 AM
 */

namespace App\Http\Requests\School;
use App\Http\Requests\MyStandardRequest;

class DepartmentRequest extends MyStandardRequest
{
    public function getFormData(){
        return $this->get('department');
    }
}