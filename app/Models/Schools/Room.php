<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    const TYPE_CLASSROOM        = 1; // 教室
    const TYPE_INTELLIGENT_CLASSROOM        = 2; //  智能教室
    const TYPE_MEETING_ROOM     = 3; // 会议室
    const TYPE_STUDENT_HOSTEL   = 4; // 学生宿舍

    protected $fillable = [
        'school_id', 'name', 'campus_id','type','building_id','description'
    ];
    public $timestamps = false;

    /**
     * 房间所属的建筑
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building(){
        return $this->belongsTo(Building::class);
    }
}
