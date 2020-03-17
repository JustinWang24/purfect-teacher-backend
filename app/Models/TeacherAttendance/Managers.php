<?php

namespace App\Models\TeacherAttendance;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Managers extends Model
{
    //
    public $table = 'teacher_attendance_managers';
    protected $fillable = [
        'teacher_attendance_id','user_id'
    ];
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
