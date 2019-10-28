<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 27/10/19
 * Time: 8:26 PM
 */

namespace Tests\Unit\Utils;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Tests\TestCase;

class TimeToolTest extends TestCase
{
    /**
     * 测试判定单双周的函数
     */
    public function testItCanGetWeekNumberByAnyGivenDate(){
        $date1 = Carbon::today();
        $date2 = Carbon::today()->subDay();

        $isOdd = GradeAndYearUtil::IsOddWeek($date1, $date2);
        $this->assertTrue($isOdd);

        $date1 = '2018-01-08';
        $date2 = '2018-01-01';
        $isOdd = GradeAndYearUtil::IsOddWeek($date1, $date2);
        $this->assertFalse($isOdd);

        $date1 = '2018-01-15';
        $date2 = '2018-01-01';
        $isOdd = GradeAndYearUtil::IsOddWeek($date1, $date2);
        $this->assertTrue($isOdd);
    }
}