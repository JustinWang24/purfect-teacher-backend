<?php

namespace App\Models\Teachers;
use App\Models\Teachers\Performance\TeacherPerformance;
use App\User;
class Teacher extends User
{
    public function performances(){
        return $this->hasMany(TeacherPerformance::class)->orderBy('year','desc');
    }
}
