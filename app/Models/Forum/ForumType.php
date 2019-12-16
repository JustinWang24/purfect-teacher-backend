<?php

namespace App\Models\Forum;


use Illuminate\Database\Eloquent\Model;

class ForumType extends Model
{
    protected $fillable = ['school_id', 'title'];

    const TYPE_FORUM = 1;
    const TYPE_TEAM  = 2;

    const TYPE_FORUM_TEXT = '论坛';
    const TYPE_TEAM_TEXT  = '社团';
}
