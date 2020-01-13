<?php

namespace App\Models\OA;

use Illuminate\Database\Eloquent\Model;

class InternalMessageFile extends Model
{
    protected $table = 'oa_internal_message_files';

    protected $fillable = ['message_id', 'path', 'name', 'type', 'size'];

}
