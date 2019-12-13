<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class ForumLike extends Model
{
    protected $table = 'forum_like';
    protected $fillable = [
        'user_id', 'forum_id'
    ];
}
