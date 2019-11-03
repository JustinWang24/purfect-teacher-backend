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

    public function campus() {
        return $this->belongsTo(Campus::class, 'campus_id', 'id');
    }

    public function room() {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }


    public function building() {
        return $this->belongsTo(Building::class, 'building_id', 'id');
    }
}
