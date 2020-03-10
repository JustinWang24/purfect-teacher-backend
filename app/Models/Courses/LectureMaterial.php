<?php

namespace App\Models\Courses;

use App\User;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Schools\Grade;
use App\Models\NetworkDisk\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LectureMaterial extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'lecture_id', 'teacher_id', 'course_id', 'media_id', 'type',
        'description', 'url', 'grade_id', 'idx'
    ];

    public function lecture(){
        return $this->belongsTo(Lecture::class);
    }

    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function media(){
        return $this->belongsTo(Media::class);
    }

    /**
     * Url 的变形, 返回全 URL 网址
     * @param $value
     * @return string
     */
    public function getUrlAttribute($value) {
        return $value ? asset($value) : '';
    }


    /**
     * 创建时间
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade() {
        return $this->belongsTo(Grade::class);
    }

    public function materialType() {
        return $this->belongsTo(LectureMaterialType::class,'type');
    }
}