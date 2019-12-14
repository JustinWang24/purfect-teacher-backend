<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialFollow extends Model
{
    protected $table = 'social_follow';
    protected $fillable = [
        'user_id', 'to_user_id'
    ];
}
