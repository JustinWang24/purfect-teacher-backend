<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'uuid','max_students_number','max_employees_number','name'
    ];

    /**
     * 最后更新学校信息的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function lastUpdatedBy(){
        if($this->last_updated_by){
            return $this->belongsTo(User::class,'last_updated_by');
        }
        return null;
    }
}
