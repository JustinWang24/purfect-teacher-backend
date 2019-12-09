<?php


namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id',
        'platform',
        'model',
        'type',
        'device_number',
        'push_id',
        'version_number'
    ];


}
