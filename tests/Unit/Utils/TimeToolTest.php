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

    public function testItCanGetBirthdayFromIdNumber(){
        $id = '110108197501114932';
        $bag = GradeAndYearUtil::IdNumberToBirthday($id);
        $birthday = $bag->getData();
        $this->assertEquals(1975,$birthday->year);
        $this->assertEquals(1,$birthday->month);
        $this->assertEquals(11,$birthday->day);

        // 测试位数不对的
        $id = '11010819750111493';
        $bag = GradeAndYearUtil::IdNumberToBirthday($id);
        $this->assertEquals('身份证号的位数不对',$bag->getMessage());

        // 测试有非数字字符的
        $id = '11010819750111d493';
        $bag = GradeAndYearUtil::IdNumberToBirthday($id);
        $this->assertEquals('身份证号必须全数字',$bag->getMessage());

        // 测试有年份明显不对的
        $id = '110108201501114932';
        $bag = GradeAndYearUtil::IdNumberToBirthday($id);
        $this->assertEquals('身份证号码显示您的年龄只有4岁, 请查证再输入',$bag->getMessage());
    }

    public function testItCanGetRightYearAndTermByAnyGivenDate(){
        $date1 = Carbon::createFromFormat('Y-m-d', '2019-12-30');
        $result = GradeAndYearUtil::GetYearAndTerm($date1);

        $this->assertEquals(2019,$result['year']);
        $this->assertEquals(1,$result['term']);

        $date2 = Carbon::createFromFormat('Y-m-d', '2020-01-30');
        $result = GradeAndYearUtil::GetYearAndTerm($date2);

        $this->assertEquals(2019,$result['year']);
        $this->assertEquals(1,$result['term']);

        $date3 = Carbon::createFromFormat('Y-m-d', '2020-05-30');
        $result = GradeAndYearUtil::GetYearAndTerm($date3);

        $this->assertEquals(2019,$result['year']);
        $this->assertEquals(2,$result['term']);

        $date3 = Carbon::createFromFormat('Y-m-d', '2020-09-30');
        $result = GradeAndYearUtil::GetYearAndTerm($date3);

        $this->assertEquals(2020,$result['year']);
        $this->assertEquals(1,$result['term']);
    }
}