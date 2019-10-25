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
     * @param $year 入学年份
     * @return int
     */
    public static function GetGradeIndexByYear($year){
        return Carbon::now()->year - intval($year) + 1;
    }
}