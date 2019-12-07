<?php

namespace App\Models\Schools;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DepartmentAdviser extends Model
{
    protected $fillable = [
        'school_id','department_id',
        'user_id','user_name','department_name'
    ];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
