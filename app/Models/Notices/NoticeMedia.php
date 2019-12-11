<?php


namespace App\Models\Notices;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;

class NoticeMedia extends Model
{
    protected $table = 'notice_medias';

    protected $fillable = ['notice_id', 'media_id','file_name','url'];

    public function media() {
        return $this->belongsTo(Media::class);
    }

    public function getUrlAttribute($value){
        return asset($value);
    }
}
