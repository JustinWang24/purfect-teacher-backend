<?php

namespace App\Models\AttendanceSchedules;

use App\Models\Users\GradeUser;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Acl\Models\Eloquent\User;

class AttendanceSchedule extends Model
{
    protected $fillable = [
        'task_id', 'user_id', 'time_slot_id', 'school_id', 'week',
    ];

    public function user()
    {
        return $this->belongsTo(GradeUser::class, 'user_id', 'user_id');
    }
}
