<?php


namespace App\Models\Notices;

use Illuminate\Database\Eloquent\Model;

class NoticeMedia extends Model
{
    protected $table = 'notice_medias';

    protected $fillable = ['notice_id', 'media_id'];
}
