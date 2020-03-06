<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 3:20 PM
 */

namespace App\Http\Requests\TimeTable;
use App\Http\Requests\MyStandardRequest;
use Carbon\Carbon;

class TimetableRequest extends MyStandardRequest
{

    /**
     * 获取课程表ID
     * @return mixed
     */
    public function getTimetableId() {
        return $this->get('time_table_id');
    }


    /**
     * 获取日期
     * @return mixed
     */
    public function getDate() {
        $date = $this->get('date', Carbon::now()->toDateString());
        return Carbon::parse($date);
    }
}