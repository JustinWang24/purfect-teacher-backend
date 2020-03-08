<?php

namespace App\Models\Version;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'versions';
    protected $fillable = [
        'schoolids',
        'user_apptype',
        'typeid',
        'isupdate',
        'version_id',
        'version_name',
        'version_downurl',
        'version_content',
        'vserion_invalidtime',
        'status',
        'create_time',
        'update_time',
    ];
}
