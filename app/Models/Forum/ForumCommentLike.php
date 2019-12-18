<?php


namespace App\Models\Forum;


use Illuminate\Database\Eloquent\Model;

class ForumCommentLike extends Model
{
    protected $table = 'forum_comment_like';
    protected $fillable = [
        'user_id', 'comment_id'
    ];
}
