<?php

namespace App\Models\Forum;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ForumCommentReply extends Model
{
    protected $table = 'forum_comment_replys';
    protected $fillable = [
        'comment_id', 'user_id', 'to_user_id', 'forum_id', 'reply','school_id','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function touser()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }
}
