<?php

namespace App\Models\OA;

use Illuminate\Database\Eloquent\Model;

class OaAttendanceLeaveAndVisits extends Model
{
    const LEAVE_TYPE = 1;
    const VISIT_TYPE = 2;

    const STATUS_DOING = 0;
    const STATUS_ACCEPT = 1;
    const STATUS_REJECT = 2;

    protected $fillable = [
        'user_id',
        'manager_id',
        'school_id',
        'reason',
        'reply',
        'start_time',
        'end_time',
        'categoryid',
        'type',
        'status',
        'address',
        'des',
    ];
    const LEAVE_CATEGORY_MAP = [
        7=>'事假',
        8=>'病假',
        9=>'调休',
        10=>'年假',
        11=>'婚假',
        12=>'产假',
        13=>'丧假',
        14=>'其它'
    ];
    const VISIT_CATEGORY_MAP = [
        6=>'外出',
    ];

    public function files()
    {
        return $this->hasMany(OaAttendanceLeaveAndVisitFiles::class, 'parent_id', 'id');
    }
}
