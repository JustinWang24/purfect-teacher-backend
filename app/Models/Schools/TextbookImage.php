<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class TextbookImage extends Model
{
    protected $fillable = [
        'media_id','textbook_id','position'
    ];
}
