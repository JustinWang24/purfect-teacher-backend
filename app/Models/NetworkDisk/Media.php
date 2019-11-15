<?php

namespace App\Models\NetworkDisk;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer $user_id
 * @property integer $type
 * @property integer $category_id
 * @property int $size
 * @property int $period
 * @property boolean $driver
 * @property string $created_at
 * @property string $file_name
 * @property string $keywords
 * @property string $url
 * @property string $description
 * @property ConferencesMedia[] $conferencesMedias
 */
class Media extends Model
{
    use CanGetByUuid;


    const DEFAULT_USER_AVATAR = 'public/user/';   // 存放用户文件路径

    const USER_SIZE = 1024 * 1024 ;  // 用户网盘空间大小 KB

    const MAY_NOT_UPLOAD = 0 ; // 不可以上传呢

    const MAY_UPLOAD = 1;  // 可以上传


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medias';

    /**
     * @var array
     */
    protected $fillable = ['uuid', 'user_id', 'type', 'category_id', 'size',
        'period', 'driver', 'created_at', 'file_name', 'keywords', 'url',
        'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conferencesMedias()
    {
        return $this->hasMany('App\Models\NetworkDisk\ConferencesMedia');
    }


    public function getUrlPathAttribute()
    {
        // 本地图片服务
        if($this->driver == 1) {
            return substr_replace($this->url,'/storage','0','6');
        }

    }



}
