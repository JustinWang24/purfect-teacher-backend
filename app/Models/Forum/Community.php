<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $table = 'communities';
    protected $fillable = [
        'school_id', 'name', 'detail', 'logo', 'pic1', 'pic2', 'pic3', 'user_id', 'status'
    ];
}
