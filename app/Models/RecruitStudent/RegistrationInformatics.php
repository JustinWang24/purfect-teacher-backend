<?php


namespace App\Models\RecruitStudent;

use Illuminate\Database\Eloquent\Model;

class RegistrationInformatics extends Model
{
    protected $fillable = ['user_id', 'school_id', 'major_id', 'name', 'whether_adjust', 'status'];
}
