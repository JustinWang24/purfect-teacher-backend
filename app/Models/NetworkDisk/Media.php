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

    const UPDATED_AT = null;


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



}
