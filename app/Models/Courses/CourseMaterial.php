<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseMaterial extends Model
{
    const TYPE_PRE      = 1;//'预习材料';
    const TYPE_LECTURE  = 2;//'课堂讲义';
    const TYPE_AFTER    = 3;//'课后阅读';
    const TYPE_HOMEWORK = 4;//'随堂作业';
    const TYPE_EXAM     = 5;//'随堂测试';

    const TYPE_PRE_TXT      = '预习材料';
    const TYPE_LECTURE_TXT  = '课堂讲义';
    const TYPE_AFTER_TXT    = '课后阅读';
    const TYPE_HOMEWORK_TXT = '随堂作业';
    const TYPE_EXAM_TXT     = '随堂测试';

    const TYPE_PRE_CLS      = '';
    const TYPE_LECTURE_CLS  = 'success';
    const TYPE_AFTER_CLS    = 'info';
    const TYPE_HOMEWORK_CLS = 'warning';
    const TYPE_EXAM_CLS     = 'danger';

    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'description',
        'index',
        'teacher_id',
        'type',
        'url',
        'media_id',
    ];

    public $casts = [
        'created_at'=>'datetime'
    ];

    /**
     * @return array
     */
    public static function AllTypes(){
        return [
            self::TYPE_PRE=>self::TYPE_PRE_TXT,
            self::TYPE_LECTURE=>self::TYPE_LECTURE_TXT,
            self::TYPE_AFTER=>self::TYPE_AFTER_TXT,
            self::TYPE_HOMEWORK=>self::TYPE_HOMEWORK_TXT,
            self::TYPE_EXAM=>self::TYPE_EXAM_TXT,
        ];
    }

    /**
     * 获取材料类型的文字
     * @param int $type
     * @return mixed|string
     */
    public static function GetTypeText($type){
        $types = self::AllTypes();

        return $types[$type] ?? 'null';
    }

    /**
     * @param $type
     * @return mixed|string
     */
    public static function GetTypeTagClass($type){
        $types = [
            self::TYPE_PRE=>self::TYPE_PRE_CLS,
            self::TYPE_LECTURE=>self::TYPE_LECTURE_CLS,
            self::TYPE_AFTER=>self::TYPE_AFTER_CLS,
            self::TYPE_HOMEWORK=>self::TYPE_HOMEWORK_CLS,
            self::TYPE_EXAM=>self::TYPE_EXAM_CLS,
        ];;

        return $types[$type] ?? 'null';
    }

    public function getUrlAttribute($value){
        if(strpos($value,'/storage/') === 0){
            // 表明是本地的文件
            return asset($value);
        }
        else{
            return strpos($value,'http') === 0 ? $value : 'http://'.$value;
        }
    }
}
