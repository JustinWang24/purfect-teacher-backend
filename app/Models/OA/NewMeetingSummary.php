<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/12
 * Time: 下午6:12
 */

namespace App\Models\OA;


use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NewMeetingSummary extends Model
{

    protected $fillable = [
        'meet_id', 'meet_user_id', 'user_id', 'url', 'file_name'
    ];

    public $hidden = ['updated_at'];

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/users/';   // 存放用户文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/users/';     // 对外的



    const DRIVER_LOCAL      = 1;
    const DRIVER_ALI_YUN    = 2;
    const DRIVER_QI_NIU     = 3;

    /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function ConvertUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }


    /**
     * url 的变形, 返回全 URL 网址
     * @param $value
     * @return string
     */
    public function getUrlAttribute($value)
    {
        return $value ? asset($value) : '';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}