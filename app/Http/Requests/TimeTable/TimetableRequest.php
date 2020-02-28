<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 3:20 PM
 */

namespace App\Http\Requests\TimeTable;
use App\Http\Requests\MyStandardRequest;

class TimetableRequest extends MyStandardRequest
{

    public function getTimetableId() {
        return $this->get('time_table_id');
    }
}