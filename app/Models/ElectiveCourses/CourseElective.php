<?php

namespace App\Models\ElectiveCourses;

use App\Models\Course;
use App\Models\Schools\Room;
use Illuminate\Database\Eloquent\Model;

class CourseElective extends Model
{
    const STATUS_WAITING = 1;
    const STATUS_STARTED = 2;
    const STATUS_ISFULL  = 3;
    const STATUS_CANCEL  = 4;

    protected $fillable = [
        'course_id', 'open_num', 'room_id', 'max_num', 'enrol_start_at', 'expired_at', 'start_year'
    ];

    /**
     * 关联的课程
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(){
        return $this->belongsTo(Course::class);
    }

    /**
     * 关联的房间
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room(){
        return $this->belongsTo(Room::class);
    }
}
