<?php

namespace App\Models\Students;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;

class ApplicationMedia extends Model
{
    protected $fillable = ['application_id' ,'media_id'];

    protected $table = 'application_medias';

    public $timestamps = false;


    public function media() {
        return $this->belongsTo(Media::class);
    }

}
