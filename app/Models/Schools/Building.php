<?php

namespace App\Models\Schools;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    const TYPE_CLASSROOM_BUILDING       = 1; // 教学楼
    const TYPE_STUDENT_HOSTEL_BUILDING  = 2; // 宿舍楼
    const TYPE_HALL                     = 3; // 礼堂, 会堂

    protected $fillable = [
        'school_id', 'name', 'campus_id','type','description'
    ];
    public $timestamps = false;

    public function rooms(){
        return $this->hasMany(Room::class);
    }

    /**
     * 建筑所归属的校区
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function campus(){
        return $this->belongsTo(Campus::class);
    }

    /**
     * 建筑所归属的学校
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(){
        return $this->belongsTo(School::class);
    }
}
