<?php


namespace App\Models\Notices;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;

class NoticeMedia extends Model
{
    protected $table = 'notice_medias';

    protected $fillable = ['notice_id', 'media_id'];

    public  $media_field = ['url'];


    public function media() {
        return $this->belongsTo(Media::class)->select($this->media_field);
    }
}
