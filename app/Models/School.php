<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Schools\Campus;
use Illuminate\Http\Request;

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

    public function campuses(){
        return $this->hasMany(Campus::class)->orderBy('name','asc');
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
