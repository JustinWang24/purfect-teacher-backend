<?php

namespace App\Models\NetworkDisk;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property integer $school_id
 * @property integer $type
 * @property integer $asterisk
 * @property integer $parent_id
 * @property integer $public
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 */
class Category extends Model
{
    use CanGetByUuid;
    /**
     * @var array
     */
    protected $fillable = ['uuid', 'owner_id', 'school_id', 'type',
        'asterisk', 'parent_id', 'public', 'created_at', 'updated_at',
        'name'];


    const TYPE_ROOT                 = 0;    // 整个系统的根目录
    const TYPE_SCHOOL_ROOT          = 1;    // 某个学校的根目录
    const TYPE_USER_ROOT            = 2;    // 某个用户的根目录
    const TYPE_SCHOOL_SUBORDINATE   = 3;    // 学校子目录
    const TYPE_USER_SUBORDINATE     = 4;    // 用户子目录


    /**
     * 当前目录的所有子目录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany(Category::class, 'parent_id');
    }


    /**
     * 给出某个目录包含的文件
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medias(){
        return $this->hasMany(Media::class);
    }


    /**
     * 当前目录的上级目录
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory(){
        return $this->belongsTo(Category::class, 'parent_id')->select(['uuid','name']);
    }

}
