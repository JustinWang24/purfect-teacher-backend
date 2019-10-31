<?php
namespace App\Models\Teachers;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected  $fillable=[
       'school_id', 'name', 'course_id', 'semester', 'formalism', 'type',
        'year', 'month', 'week', 'day', 'exam_time', 'from', 'to', 'status'
    ];



    /**
     * 获取考场
     */
    public function examsRoom()
    {
        return $this->hasMany(ExamsPlansRoom::class, 'exam_id', 'id');
    }

    /**
     * 获取课程
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id','id');
    }




}
