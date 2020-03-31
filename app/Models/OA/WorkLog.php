<?php

namespace App\Models\OA;

use App\Models\Students\StudentProfile;
use App\Models\Teachers\TeacherProfile;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{

    protected $table = 'oa_teacher_work_logs';

    protected $fillable = [
        'user_id', 'collect_user_id', 'title', 'content', 'type', 'status', 'send_user_id', 'send_user_name'
    ];

    const TYPE_READ   = 1;  // 已接收
    const TYPE_SENT   = 2;  // 已发送
    const TYPE_DRAFTS = 3;  // 未发送

    const STATUS_ERROR = 0;  // 不显示
    const STATUS_NORMAL = 1; // 显示

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function profile()
    {
        return $this->belongsTo(TeacherProfile::class,'user_id','user_id');
    }

    public function sendUserProfile()
    {
        return $this->belongsTo(TeacherProfile::class,'send_user_id','user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function read()
    {
        return $this->hasOne(WorkLogRead::class,'work_id');
    }
}
