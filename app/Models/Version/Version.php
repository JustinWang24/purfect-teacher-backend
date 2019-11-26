<?php

namespace App\Models\Version;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $fillable = [
        'code', 'name', 'download_url', 'local_path',
    ];
}
