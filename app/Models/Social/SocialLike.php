<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialLike extends Model
{
    protected $table = 'social_like';
    protected $fillable = [
        'user_id', 'to_user_id'
    ];
}
