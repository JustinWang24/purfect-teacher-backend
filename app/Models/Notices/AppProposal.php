<?php

namespace App\Models\Notices;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AppProposal extends Model
{

    // 学生端反馈类型
    const TYPE_1 = '易码通';
    const TYPE_2 = '办事大厅';
    const TYPE_3 = '学习';
    const TYPE_4 = '生活';
    const TYPE_5 = '就业';
    const TYPE_6 = '其他';
    // 教师端反馈类型
    const TYPE_7 = '办公';
    const TYPE_8 = '教学';
    const TYPE_9 = '教务';
    const TYPE_10 = '管理';


    protected $fillable = ['user_id', 'type', 'content'];

    public static function allType()
    {
        return [
            1 => self::TYPE_1,
            2 => self::TYPE_2,
            3 => self::TYPE_3,
            4 => self::TYPE_4,
            5 => self::TYPE_5,
            6 => self::TYPE_6,
            7 => self::TYPE_7,
            8 => self::TYPE_8,
            9 => self::TYPE_9,
            10 => self::TYPE_10,
        ];
    }

    public function getTypeText()
    {
        return self::allType()[$this->type];
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(AppProposalImage::class);
    }


}
