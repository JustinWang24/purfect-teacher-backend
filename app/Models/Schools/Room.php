<?php

namespace App\Models\Schools;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
    const TYPE_CLASSROOM        = 1;                // 教室
    const TYPE_INTELLIGENT_CLASSROOM = 2;    // 智慧教室
    const TYPE_MEETING_ROOM     = 3;                // 会议室
    const TYPE_OFFICE           = 4;                // 会议室
    const TYPE_STUDENT_HOSTEL   = 5;                // 学生宿舍

    const TYPE_CLASSROOM_TXT        = '教室';                //
    const TYPE_INTELLIGENT_CLASSROOM_TXT = '智慧教室';    //
    const TYPE_MEETING_ROOM_TXT     = '会议室';                //
    const TYPE_OFFICE_TXT           = '教师办公室';                //
    const TYPE_STUDENT_HOSTEL_TXT   = '学生宿舍';                //

    protected $fillable = [
        'school_id', 'name', 'campus_id','type','building_id','description','seats'
    ];
    public $timestamps = false;

    /**
     * 房间所属的建筑
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building(){
        return $this->belongsTo(Building::class);
    }

    /**
     * 关联的学校
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(){
        return $this->belongsTo(School::class);
    }

    public static function AllTypes(){
        return [
            self::TYPE_CLASSROOM => self::TYPE_CLASSROOM_TXT,
            self::TYPE_INTELLIGENT_CLASSROOM => self::TYPE_INTELLIGENT_CLASSROOM_TXT,
            self::TYPE_MEETING_ROOM => self::TYPE_MEETING_ROOM_TXT,
            self::TYPE_OFFICE => self::TYPE_OFFICE_TXT,
            self::TYPE_STUDENT_HOSTEL => self::TYPE_STUDENT_HOSTEL_TXT,
        ];
    }

    /**
     * 获取类型的名称
     * @return string
     */
    public function getTypeText(){
        return self::AllTypes()[$this->type];
    }

    /**
     * 获取类型的文字, badge 的形式
     * @return string
     */
    public function getTypeHtmlText(){
        $text = self::AllTypes()[$this->type];
        $class = 'light';
        if($this->type === self::TYPE_INTELLIGENT_CLASSROOM){
            $class = 'primary';
        }
        return '<span class="badge badge-'.$class.'">'.$text.'</span>';
    }
}
