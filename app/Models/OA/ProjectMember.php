<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $table = 'oa_project_members';
    public $timestamps = false;
    protected $fillable = ['user_id', 'project_id'];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
