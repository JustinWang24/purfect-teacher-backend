<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialFollowed extends Model
{
    protected $table = 'social_followed';
    protected $fillable = [
        'user_id', 'from_user_id'
    ];
}
