<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class ForumLike extends Model
{
    protected $fillable = [
        'user_id', 'forum_id'
    ];
}
