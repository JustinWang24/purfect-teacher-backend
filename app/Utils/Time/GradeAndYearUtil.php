<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 9:39 AM
 */

namespace App\Utils\Time;
use App\Models\Schools\SchoolConfiguration;
use App\Models\Timetable\TimeSlot;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use App\Dao\Timetable\TimeSlotDao;

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
    const TYPE_ONLY_AVAILABLE_WEEKS  = 4; // 表示在指定区间内有课

    /**
     * 将如果年份转成年级的数字, 根据当前的年份. 比如 2019 届在 2019 年就是 1 年级, 到 2020年就是 2 年级
     * @param $year: 入学年份
     * @return int
     */
    public static function GetGradeIndexByYear($year){
        return Carbon::now(self::TIMEZONE_CN)->year - intval($year) + 1;
    }

    /**
     * 对于任意给定的日期，都可以返回正确的学年与学期
     * @param Carbon $date
     * @return array
     */
    public static function GetYearAndTerm(Carbon $date){
        $year = $date->year;
        $term = 1;
        if($date->month >= 9){
            // 不变
        }
        elseif ($date->month < 9 && $date->month > 2){
            $year = $year - 1;
            $term = 2;
        }
        elseif ($date->month <= 2){
            $year = $year - 1;
        }
        return [
            'year'=>$year,'term'=>$term
        ];
    }

    /**
     * 获取所有的年级年份
     * @param int $num
     * @return array
     */
    public static function GetAllYears($num = 2){
        $year = intval(date('Y'));
        // 如果已经过了8月份， 则表示新的1年了
        $month = intval(date('m'));
        if($month<8){
            $year = $year - 1;
        }
        return range($year - $num, $year);
    }

    /**
     * 获取当前的额 timeslot 对象
     *
     * @param null $time
     * @param null $schoolId
     * @return TimeSlot|null
     */
    public static function GetTimeSlot($time = null, $schoolId = null){
        $dao = new TimeSlotDao();
        $timeSlots = $dao->getAllStudyTimeSlots($schoolId ?? session('school.id'));
        if(is_null($time)){
            $time = Carbon::now(self::TIMEZONE_CN);
        }

        $slot = null;

        foreach ($timeSlots as $timeSlot) {
            if($timeSlot->from <= $time->format('H:i:s') && $timeSlot->to > $time->format('H:i:s')){
                $slot = $timeSlot;
            }
        }
        return $slot;
    }

  /**
   * 获取当前的季节类型
   * @param SchoolConfiguration $config
   * @param null $now
   * @return void
   */
    public static function GetCurrentSeason(SchoolConfiguration $config, Carbon $now = null){
        if (empty($now)){
            $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);
        }
        $mockDate = Carbon::createFromFormat('Y-m-d',SchoolConfiguration::FAKE_YEAR.'-'.$now->month.'-'.$now->day)->format('Y-m-d');
        $seasonType = TimeSlot::SEASONS_WINTER_AND_SPRINT;
        if($config->summer_start_date->format('Y-m-d') <= $mockDate && $mockDate < $config->winter_start_date->format('Y-m-d')){
            $seasonType = TimeSlot::SEASONS_SUMMER_AND_AUTUMN;
        }
        return $seasonType;
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

    /**
     * 从身份证中解析生日的方法
     *
     * @param $idNumber
     * @return IMessageBag
     */
    public static function IdNumberToBirthday($idNumber){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        if(strlen($idNumber) === 18){
            // 必须全数字的
            if(preg_match("/^\d*$/",$idNumber)){
                // 取出代表生日的部分
                try{
                    $birthday =  Carbon::createFromFormat('Ymd',substr($idNumber, 6,8));
                    // 从理论上讲, 最小的入学年龄应该是 5 岁, 要检查一下
                    $age = Carbon::now()->diffInYears($birthday);
                    if($age > 5){
                        $bag->setCode(JsonBuilder::CODE_SUCCESS);
                        $bag->setData($birthday);
                    }
                    else{
                        $bag->setMessage('身份证号码显示您的年龄只有'.$age.'岁, 请查证再输入');
                    }
                }catch (\Exception $exception){
                    $bag->setMessage('身份证号中表示生日部分格式不对');
                }
            }else{
                $bag->setMessage('身份证号必须全数字');
            }
        }else{
            $bag->setMessage('身份证号的位数不对');
        }

        return $bag;
    }


    /**
     * 获取当前的额 timeslot 对象
     *
     * @param null $time
     * @param null $schoolId
     * @return TimeSlot|null
     */
    public static function GetUnEndTimeSlot($time = null, $schoolId = null){
        $dao = new TimeSlotDao();
        $timeSlots = $dao->getAllStudyTimeSlots($schoolId ?? session('school.id'));
        if(is_null($time)){
            $time = Carbon::now(self::TIMEZONE_CN);
        }

        $slot = null;

        foreach ($timeSlots as $timeSlot) {
            if($timeSlot->to > $time->format('H:i:s')){
                $slot[] = $timeSlot;
            }
        }
        return $slot;
    }
}
