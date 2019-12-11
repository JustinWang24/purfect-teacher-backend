<?php

namespace App\Models\Teachers;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;

class ConferencesMedia extends Model
{
    protected  $table = 'conferences_medias';


    protected  $fillable=[
        'conference_id','media_id'
    ];

    public $media_field = ['url'];


    public function media()
    {
        return $this->belongsTo(Media::class)->select($this->media_field);
    }

}
