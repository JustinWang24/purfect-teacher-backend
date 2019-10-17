<?php

namespace App\Models\Schools;

use App\Models\Users\GradeUser;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id', 'name', 'description','last_updated_by'
    ];

    public function institutes(){
        return $this->hasMany(Institute::class);
    }

    public function employeesCount(){
        return GradeUser::where('campus_id', $this->id)->where('user_type',User::TYPE_EMPLOYEE)->count();
    }

    public function studentsCount(){
        return GradeUser::where('campus_id', $this->id)->where('user_type',User::TYPE_STUDENT)->count();
    }
}
