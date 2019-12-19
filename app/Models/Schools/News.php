<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    const TYPE_NEWS     = 1; // 类型为动态, 未来可以移植为科研 等其他类型
    const TYPE_SCIENCE  = 2; // 科技文章
    const TYPE_ARTICLE  = 3; // 简介
    const TYPE_CAMPUS   = 4; // 校园风采
    const PUBLISH_NO    = 0; // 未发布
    const PUBLISH_YES   = 1; // 已发布

    const PUBLISH_NO_TXT    =  "未发布";
    const PUBLISH_YES_TXT   =  "已发布";

    const TYPE_NEWS_TXT     = '动态'; // 类型为动态, 未来可以移植为科研 等其他类型
    const TYPE_SCIENCE_TXT  = '科技成果'; // 科技文章
    const TYPE_ARTICLE_TXT  = '简介'; // 简介
    const TYPE_CAMPUS_TXT  = '校园风采'; // 简介

    protected $fillable = [
        'school_id','title','publish','type','tags'
    ];

    public $casts = [
        'publish'=>'boolean',
        'tags'=>'array',
    ];

    public function sections(){
        return $this->hasMany(NewsSection::class)->orderBy('position','asc');
    }

    /**
     * 文章的所有类型
     * @return array
     */
    public static function Types(){
        return [
            self::TYPE_NEWS=>self::TYPE_NEWS_TXT,
            self::TYPE_SCIENCE=>self::TYPE_SCIENCE_TXT,
            self::TYPE_ARTICLE=>self::TYPE_ARTICLE_TXT,
            self::TYPE_CAMPUS=>self::TYPE_CAMPUS_TXT,
        ];
    }

    public static function TypeText($type){
        return self::Types()[intval($type)];
    }
}
