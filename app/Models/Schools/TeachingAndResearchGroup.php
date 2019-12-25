<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class TeachingAndResearchGroup extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'type','name','user_id','user_name','school_id'
    ];

    public function members(){
        return $this->hasMany(TeachingAndResearchGroupMember::class,'group_id');
    }
}
