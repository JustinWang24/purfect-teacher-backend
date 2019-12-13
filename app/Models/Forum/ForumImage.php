<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class ForumImage extends Model
{
    protected  $fillable = ['forum_id', 'image', 'video', 'see_num', 'cover'];
}
