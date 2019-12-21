<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class TeachingAndResearchGroupMember extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'group_id','user_id','user_name'
    ];

    public function teachingAndResearchGroup(){
        return $this->belongsTo(TeachingAndResearchGroup::class, 'group_id');
    }
}
