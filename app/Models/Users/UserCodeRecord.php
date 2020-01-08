<?php


namespace App\Models\Users;


use App\User;
use App\Models\Schools\Facility;
use Illuminate\Database\Eloquent\Model;

class UserCodeRecord extends Model
{

    protected $fillable = [
        'user_id', 'school_id', 'facility_id', 'type', 'desc'
    ];

    const TYPE_VALIDATION = 1;
    const TYPE_SPENDING   = 2;

    const TYPE_VALIDATION_TEXT = '验证';
    const TYPE_SPENDING_TEXT   = '消费';

    // 二维码识别标识
    const IDENTIFICATION_APP = 'app'; // 用于生成 学生,教师端二维码
    const IDENTIFICATION_COURSE = 'course'; // 用于生成 补课二维码

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function facility() {
        return $this->belongsTo(Facility::class);
    }


    public function allType() {
        return [
            self::TYPE_VALIDATION => self::TYPE_VALIDATION_TEXT,
            self::TYPE_SPENDING   => self::TYPE_SPENDING_TEXT,
        ];
    }


    public function typeText() {
        $data = $this->allType();
        return $data[$this->type]??'';
    }
}
