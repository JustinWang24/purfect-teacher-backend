<?php

namespace App\Models\Teachers;


use Illuminate\Database\Eloquent\Model;
class Teacher extends Model
{
    public  function  teacherProfile()
    {
        $this->hasOne(teacherProfile::class);
    }


}
