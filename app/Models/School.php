<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Schools\Campus;
use Illuminate\Http\Request;
use App\Models\Timetable\TimeSlot;

class School extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'uuid','max_students_number','max_employees_number','name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastUpdatedBy(){
        return $this->belongsTo(User::class,'last_updated_by');
    }

    /**
     * 学校包含的校区
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campuses(){
        return $this->hasMany(Campus::class)->orderBy('name','asc');
    }

    /**
     * 学校预制的时间段
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeFrame(){
        return $this->hasMany(TimeSlot::class)->select(['id','name','type','from','to'])
            ->orderBy('from','asc');
    }

    /**
     * 将学校信息保存在 session 中
     * @param Request $request
     */
    public function savedInSession(Request $request){
        $request->session()->put('school.id',$this->id);
        $request->session()->put('school.uuid',$this->uuid);
        $request->session()->put('school.name',$this->name);
    }
}
