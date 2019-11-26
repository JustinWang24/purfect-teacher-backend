<?php

namespace App\Models\Schools;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;

class NewsSection extends Model
{
    protected $fillable = [
        'news_id',
        'position',
        'media_id',
        'content',
    ];

    public $timestamps = false;

    public function media(){
        return $this->belongsTo(Media::class);
    }

    /**
     * 判断此段落是否为多媒体
     * @return bool
     */
    public function isMultiMedia(){
        return !$this->media_id;
    }
}
