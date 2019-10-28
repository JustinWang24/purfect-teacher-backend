<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 9:39 AM
 */

namespace App\Utils\Time;
use Carbon\Carbon;

class GradeAndYearUtil
{
    const DEFAULT_FORMAT_DATE = 'Y年m月d';
    const DEFAULT_FORMAT_DATETIME = 'Y年m月d H时i分';
    const TIMEZONE_CN = 'Asia/Shanghai';
    // 和单双周相关
    const WEEK_ODD              = 1; // 单周
    const WEEK_EVEN             = 2; // 双周
    const TYPE_EVERY_WEEK       = 1; // 表示每周都有课
    const TYPE_EVERY_ODD_WEEK   = 2; // 表示每单周都有课
    const TYPE_EVERY_EVEN_WEEK  = 3; // 表示每双周都有课

    /**
     * 将如果年份转成年级的数字, 根据当前的年份. 比如 2019 届在 2019 年就是 1 年级, 到 2020年就是 2 年级
     * @param $year: 入学年份
     * @return int
     */
    public static function GetGradeIndexByYear($year){
        return Carbon::now()->year - intval($year) + 1;
    }

    /**
     * 转换 js 提交的日期时间字符串为 carbon 对象
     * js 提交的日期时间字符串: Thu Oct 31 2019 00:00:00 GMT+0800 (China Standard Time)
     * @param $str
     * @return Carbon|null
     */
    public static function ConvertJsTimeToCarbon($str){
        $arr = explode(' (', $str);
        $datetimeString = $arr[0] ?? null;
        return $datetimeString ? Carbon::parse($datetimeString)->addDay() : $datetimeString;
    }

    /**
     * 判定第一个给定的日期相对于第二个给定的日期, 是否中间的间隔周数为单数
     * @param null $date
     * @param null $startFrom
     * @return boolean
     */
    public static function IsOddWeek($date = null, $startFrom = null){
        if(!$date){
            $date = Carbon::today(self::TIMEZONE_CN);
        }elseif(is_string($date)){
            $date = Carbon::createFromFormat('Y-m-d',$date);
        }

        if (!$startFrom){
            $startFrom = Carbon::today(self::TIMEZONE_CN)->startOfYear();
        }elseif(is_string($startFrom)){
            $startFrom = Carbon::createFromFormat('Y-m-d',$startFrom);
        }

        $dayOfWeeks = $date->diffInWeeks($startFrom);

        return $dayOfWeeks % 2 === 0;
    }
}