<?php

namespace App\Models\Schools;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;

class TextbookImage extends Model
{
    protected $fillable = [
        'textbook_id','media_id','position','url'
    ];

    public $timestamps = false;

    public function media(){
        return $this->belongsTo(Media::class);
    }

    public function textbook(){
        return $this->belongsTo(Textbook::class);
    }

    public function getUrlAttribute($value){
        return asset($value);
    }
}
