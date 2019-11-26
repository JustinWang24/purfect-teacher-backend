<?php

namespace App\Models\Students;

use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;


/**
 * Class ApplicationType
 * @property integer $id
 * @property string $name
 * @property integer $school_id
 * @package App\Models\Students
 */
class ApplicationType extends Model
{

    protected $fillable = ['name', 'school_id'];

    public $timestamps = false;


    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;
    const STATUS_OPEN_TEXT   = '开启';
    const STATUS_CLOSE_TEXT  = '关闭';

    public $media_field = ['url'];


    public function getStatusText($status) {
        $data =  $this->getAllStatus();
        return $data[$status];
    }


    public function getAllStatus() {
        return [
            self::STATUS_CLOSE  => self::STATUS_CLOSE_TEXT,
            self::STATUS_OPEN   => self::STATUS_OPEN_TEXT,
        ];
    }


     public function media() {
        return $this->belongsTo(Media::class)->select($this->media_field);
    }
}
