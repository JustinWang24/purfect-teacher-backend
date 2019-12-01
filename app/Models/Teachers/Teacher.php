<?php

namespace App\Models\Teachers;
use App\Models\Teachers\Performance\TeacherPerformance;
use App\User;
class Teacher extends User
{
    protected $table = 'users';

    public function performances(){
        return $this->hasMany(TeacherPerformance::class, 'user_id')->orderBy('year','desc');
    }
}
