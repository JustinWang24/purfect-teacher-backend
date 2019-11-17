<?php

namespace App\Models\NetworkDisk;

use Illuminate\Database\Eloquent\Model;
use App\User;

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
        return $this->hasMany(Media::class)->orderBy('updated_at','desc');
    }

    /**
     * 当前目录的上级目录
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory(){
        return $this->belongsTo(Category::class, 'parent_id')->select(['uuid','name']);
    }

    /**
     * 是否目录对指定用户可写入
     * @param User $user
     * @return bool
     */
    public function isWritableByUser(User $user){
        if($user->isOperatorOrAbove()){
            return true;
        }
        return $this->isOwnedByUser($user);
    }

    /**
     * 是否目录对指定用户读取
     * @param User $user
     * @return bool
     */
    public function isReadableByUser(User $user){
        if($user->isOperatorOrAbove()){
            return true;
        }
        return $this->isOwnedByUser($user);
    }

    /**
     * 是否目录是给定用户的拥有者
     * @param User $user
     * @return bool
     */
    public function isOwnedByUser(User $user){
        return $user->id === $this->owner_id;
    }

    /**
     * 目录是否为给定用户的根目录
     * @param User $user
     * @return bool
     */
    public function isRootOf(User $user){
        if($user->isSuperAdmin() || $user->isOperatorOrAbove()){
            return false;
        }
        elseif ($user->isSchoolManager()){
            return $this->type === self::TYPE_SCHOOL_ROOT;
        }
        elseif($user->isStudent() || $user->isTeacher() || $user->isEmployee()){
            return $this->type === self::TYPE_USER_ROOT;
        }
    }
}
