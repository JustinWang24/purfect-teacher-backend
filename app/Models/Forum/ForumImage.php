<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class ForumImage extends Model
{
    protected  $fillable = ['forum_id', 'image', 'video', 'see_num', 'cover', 'type'];


    /**
     * 转换图片网络路径
     * @param $value
     * @return string
     */
    public static function getImageAttribute($value)
    {
        return $value ? asset($value) : '';
    }


    /**
     * 转换视频网络路径
     * @param $value
     * @return string
     */
    public static function getVideoAttribute($value)
    {
        return $value ? asset($value) : '';
    }

    /**
     * 转换视频封面网络路径
     * @param $value
     * @return string
     */
    public static function getCoverAttribute($value)
    {
        return $value ? asset($value) : '';
    }
}
