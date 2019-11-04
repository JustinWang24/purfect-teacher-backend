<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @property integer $id
 * @property string $facility_number
 * @property string $facility_name
 * @property int $school_id
 * @property int $campuse_id
 * @property int $building_id
 * @property int $room_id
 * @property string $detail_addr
 * @property boolean $status
 * @property boolean $type
 */
class Facility extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'facilitys';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['facility_number', 'facility_name', 'school_id', 'campus_id', 'building_id', 'room_id', 'detail_addr', 'status', 'type'];


    const TYPE_MONITORING  = 1;
    const TYPE_ENTRANCE_GUARD  = 2;
    const TYPE_CLASS_SIGN  = 3;
    const TYPE_CLASS_CLASSROOM  = 4;



    const TYPE_MONITORING_TEXT  = '监控设备';
    const TYPE_ENTRANCE_GUARD_TEXT  = '门禁设备';
    const TYPE_CLASS_SIGN_TEXT  = '班牌设备';
    const TYPE_CLASS_CLASSROOM_TEXT  = '教室设备';




    public function campus() {
        return $this->belongsTo(Campus::class, 'campus_id', 'id');
    }

    public function room() {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }


    public function building() {
        return $this->belongsTo(Building::class, 'building_id', 'id');
    }


    /**
     * 获取type属性
     * @return string
     */
    public function getTypeTextAttribute() {
        switch ($this->type) {
            case self::TYPE_MONITORING:
                return self::TYPE_MONITORING_TEXT;break;
            case self::TYPE_ENTRANCE_GUARD:
                return self::TYPE_ENTRANCE_GUARD_TEXT;break;
            case self::TYPE_CLASS_SIGN:
                return self::TYPE_CLASS_SIGN_TEXT;break;
            case self::TYPE_CLASS_CLASSROOM:
                return self::TYPE_CLASS_CLASSROOM_TEXT;
            default : return '';

        }
    }
}
