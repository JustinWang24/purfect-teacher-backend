<?php
namespace App\Models\Banner;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'school_id', 'campus_id', 'posit', 'type', 'sort',
        'title', 'image_url', 'content', 'external','public','clicks'
    ];

    // 展示位置
    const POSIT_HOME  = 0;
    const POSIT_1  = 1;
    const POSIT_2  = 2;
    const POSIT_3  = 3;
    const POSIT_4  = 4;
    const POSIT_5  = 5;
    const POSIT_6  = 6;
    const POSIT_7  = 7;
    const POSIT_8  = 8;
    const POSIT_9  = 9;
    const POSIT_10  = 10;
    const POSIT_11  = 11;
    const POSIT_12  = 12;

    const POSIT_HOME_TEXT = '首页';
    const POSIT_TEXT_1 = '招生资源位';
    const POSIT_TEXT_2 = '校园资源位';
    const POSIT_TEXT_3 = '学习资源位';
    const POSIT_TEXT_4 = '培训资源位';
    const POSIT_TEXT_5 = '购物资源位';
    const POSIT_TEXT_6 = '美食资源位';
    const POSIT_TEXT_7 = '超市资源位';
    const POSIT_TEXT_8 = '其他店铺';
    const POSIT_TEXT_9 = '天天特价资源位';
    const POSIT_TEXT_10 = '品牌闪购资源位';
    const POSIT_TEXT_11 = '就业资源位';
    const POSIT_TEXT_12 = '教师首页';


    // 展示类型
    const TYPE_NO_ROTATION = 0;
    const TYPE_IMAGE_WRITING = 1;
    const TYPE_URL = 2;

    const TYPE_NO_ROTATION_TEXT = '无跳转';
    const TYPE_IMAGE_WRITING_TEXT = '图文';
    const TYPE_URL_TEXT = 'URL';

    const STATUS_OPEN  = 1;
    const STATUS_CLOSE = 0;

    const STATUS_OPEN_TEXT = '显示';
    const STATUS_CLOSE_TEXT = '不显示';

    public $casts = [
        'public'=>'boolean'
    ];

    public static function allPosit(){
        return [
            self::POSIT_HOME => self::POSIT_HOME_TEXT,
            self::POSIT_1 => self::POSIT_TEXT_1,
            self::POSIT_2 => self::POSIT_TEXT_2,
            self::POSIT_3 => self::POSIT_TEXT_3,
            self::POSIT_4 => self::POSIT_TEXT_4,
            self::POSIT_5 => self::POSIT_TEXT_5,
            self::POSIT_6 => self::POSIT_TEXT_6,
            self::POSIT_7 => self::POSIT_TEXT_7,
            self::POSIT_8 => self::POSIT_TEXT_8,
            self::POSIT_9 => self::POSIT_TEXT_9,
            self::POSIT_10 => self::POSIT_TEXT_10,
            self::POSIT_11 => self::POSIT_TEXT_11,
            self::POSIT_12 => self::POSIT_TEXT_12,
        ];
    }

    public static function allType(){
        return [
            self::TYPE_NO_ROTATION => self::TYPE_NO_ROTATION_TEXT,
            self::TYPE_IMAGE_WRITING => self::TYPE_IMAGE_WRITING_TEXT,
            self::TYPE_URL => self::TYPE_URL_TEXT,
        ];
    }

    public static function GetAllPositByPosit($posit){
        return self::allPosit()[$posit] ?? null;
    }

    public static function GetAllTypeByType($type){
        return self::allType()[$type] ?? null;
    }

    public function getTypeText(){
        return self::allType()[$this->type];
    }

    public function getPositionText(){
        return self::allPosit()[$this->posit];
    }

    public function isPublicText(){
        return $this->public ? '不需登录' : '需要登录';
    }
}
