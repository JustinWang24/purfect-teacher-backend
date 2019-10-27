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
}