<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    protected $fillable = [
        'user_id', 'content', 'forum_id'
    ];

    public  function reply()
    {
        return $this->hasMany(ForumCommentReply::class, 'comment_id', 'id');
    }

}
