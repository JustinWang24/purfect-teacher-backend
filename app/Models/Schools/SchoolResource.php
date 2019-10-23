<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class SchoolResource extends Model
{
    protected $fillable = ['school_id', 'name', 'path', 'video_path', 'type', 'size', 'format', 'width', 'height'];

    const TYPE_ALL    = 0;
    const TYPE_IMAGE  = 1;
    const TYPE_VIDEO  = 2;
    const PAGE_NUMBER = 1;


}
