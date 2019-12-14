<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class Community_member extends Model
{
    protected $table = 'communities_member';
    protected $fillable = [
        'school_id', 'community_id', 'user_name', 'reason', 'user_id', 'status'
    ];
}
