<?php

namespace App\Models\RecruitStudent;

use Illuminate\Database\Eloquent\Model;

class RecruitNote extends Model
{
    public $timestamps = false;
    protected $fillable = ['school_id','plan_id','content'];
}
