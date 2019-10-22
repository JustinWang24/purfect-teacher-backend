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

    /**
     * 校区包含的学院
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function institutes(){
        return $this->hasMany(Institute::class);
    }

    /**
     * 校区内所有建筑物
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buildings(){
        return $this->hasMany(Building::class);
    }

    /**
     * 校区的教学楼
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classroomBuildings(){
        return $this->hasMany(Building::class)->where('type',Building::TYPE_CLASSROOM_BUILDING);
    }

    /**
     * 校区的宿舍楼
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hostels(){
        return $this->hasMany(Building::class)->where('type',Building::TYPE_STUDENT_HOSTEL_BUILDING);
    }

    /**
     * 校区的食堂/会堂等
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function halls(){
        return $this->hasMany(Building::class)->where('type',Building::TYPE_HALL);
    }

    public function employeesCount(){
        return GradeUser::where('campus_id', $this->id)->where('user_type',User::TYPE_EMPLOYEE)->count();
    }

    public function studentsCount(){
        return GradeUser::where('campus_id', $this->id)->where('user_type',User::TYPE_STUDENT)->count();
    }
}
