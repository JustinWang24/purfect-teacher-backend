<?php

namespace App\Models\Schools;

use App\Models\Teachers\Teacher;
use App\User;
use Illuminate\Database\Eloquent\Model;

class GradeManager extends Model
{
    protected $fillable = [
        'school_id',
        'grade_id',
        'adviser_id',
        'adviser_name',
        'monitor_id',
        'monitor_name',
    ];

    const ADVISER = '班主任';
    const MONITOR = '班长';
    const STUDENT = '学生';
    const GROUP = '团支书';

    /**
     * 班长
     */
    public function monitor(){
        return $this->hasOne(User::class, 'id','monitor_id');
    }

    /**
     * 团支书
     */
    public function group(){
        return $this->hasOne(User::class, 'id', 'group_id');
    }

    /**
     * 关联的班主任
     */
    public function adviser(){
        return $this->hasOne(User::class, 'id','adviser_id');
    }

    public function grade()
    {
        return $this->hasOne(Grade::class, 'id', 'grade_id');
    }
}
