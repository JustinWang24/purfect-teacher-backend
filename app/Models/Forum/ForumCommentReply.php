<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class ForumCommentReply extends Model
{
    protected $fillable = [
        'comment_id', 'user_id', 'to_user_id', 'forum_id', 'reply'
    ];
}
