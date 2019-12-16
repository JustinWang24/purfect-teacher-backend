<?php

namespace App\Models\Timetable;

use App\Models\Course;
use App\Models\Schools\Building;
use App\Models\Schools\Grade;
use App\Models\Schools\Room;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimetableItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'year','term',
        'course_id','time_slot_id',
        'building_id','room_id',
        'teacher_id','grade_id',
        'weekday_index','repeat_unit',
        'at_special_datetime','to_special_datetime',
        'to_replace','last_updated_by',
        'school_id','published'
    ];

    public $casts = [
        'published' => 'boolean', // 是否本 item 是发布状态
        'at_special_datetime' => 'datetime', // 调课记录的开始事件
        'to_special_datetime' => 'datetime', // 调课记录的结束时间
    ];

    public function getPublishedTextAttribute(){
        return $this->published ? '正式' : '草稿(待确认)';
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function building(){
        return $this->belongsTo(Building::class);
    }

    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function teacher(){
        return $this->belongsTo(User::class);
    }

    public function grade(){
        return $this->belongsTo(Grade::class);
    }

    public function replacement(){
        return $this->belongsTo(self::class,'to_replace');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function describeItself(){
        return $txt = $this->grade->name . ' - ' . $this->course->name;
    }

    public function itemEnquiries(){
        return $this->hasMany(TimetableItemEnquiry::class);
    }

    public function timeSlot(){
        return $this->belongsTo(TimeSlot::class);
    }
}
