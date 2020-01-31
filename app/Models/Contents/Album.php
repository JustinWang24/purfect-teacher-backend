<?php

namespace App\Models\Contents;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    const TYPE_PHOTO = 1;
    const TYPE_VIDEO = 2;

    protected $fillable = [
        'school_id','type','title','url'
    ];

    public function getUrlAttribute($value){
        return asset($value);
    }
}
