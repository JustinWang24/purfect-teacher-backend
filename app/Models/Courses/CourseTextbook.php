<?php

namespace App\Models\Courses;

use App\Models\Course;
use App\Models\Schools\Textbook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $course_id
 * @property int $school_id
 * @property int $textbook_id
 */
class CourseTextbook extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['course_id', 'school_id', 'textbook_id'];

    /**
     * @var array
     */
    protected $hidden = ['updated_at', 'deleted_at'];


    /**
     * 关联教材
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function textbook()
    {
        return $this->belongsTo(Textbook::class);
    }

    /**
     * 关联的课程
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
