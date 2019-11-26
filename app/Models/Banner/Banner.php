<?php


namespace App\Models\Banner;

use App\Models\Schools\Campus;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['school_id', 'campus_id', 'posit', 'type', 'sort', 'title', 'image_url', 'content', 'external'];


    // 展示位置
    const POSIT_HOME  = 1;

    const POSIT_HOME_TEXT = '首页';


    // 展示类型
    const TYPE_NO_ROTATION = 0;
    const TYPE_IMAGE_WRITING = 1;
    const TYPE_URL = 2;

    const TYPE_NO_ROTATION_TEXT = '无跳转';
    const TYPE_IMAGE_WRITING_TEXT = '图文';
    const TYPE_URL_TEXT = 'URL';


    public static function allPosit(){
        return [
            self::POSIT_HOME => self::POSIT_HOME_TEXT,
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

    public function campus()
    {
      return  $this->hasOne(Campus::class, 'id', 'campus_id');
    }


}
