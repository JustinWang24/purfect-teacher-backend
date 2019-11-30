<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 27/10/19
 * Time: 10:26 PM
 */

namespace Tests\Unit\Dao;

use App\Dao\Timetable\TimetableItemDao;
use App\Utils\Time\GradeAndYearUtil;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class TimetableItemDaoTest extends TestCase
{
    // 检查一个课程表项目, 是否其时段已经被占用的逻辑
    // 情况 1: 如果课程表项目, 它的重复周期是每周有效, 那么插入前要检查, 只要该时段有任意类型的项目, 则都不可插入
    public function testItShallRejectWhenTypeEveryWeekItemIsOccupied(){
        $data = $this->_mockData(
            ['weekday_index'=>1, 'time_slot_id'=>1, 'repeat_unit'=>GradeAndYearUtil::TYPE_EVERY_WEEK]
        ); // 获取 周一第一个时段, 每周重复的测试数据进行插入

        $dao = new TimetableItemDao();
        $item = $dao->createTimetableItem($data); // 插入到数据库中

        $this->assertNotNull($item);

        $testData = $this->_mockData(
            ['weekday_index'=>1, 'time_slot_id'=>1, 'repeat_unit'=>GradeAndYearUtil::TYPE_EVERY_WEEK]
        ); // 获取 周一第一个时段, 每周重复的测试数据

        $result = $dao->hasAnyOneTakenThePlace($testData);
        $this->assertNotFalse($result);
        try{
            DB::table('timetable_items')->where('year','=',2000)->delete();
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
        }
    }

    // 情况 2: 如果课程表项目, 它的重复周期是 单 / 双 周有效, 那么插入前要检查
    public function testItShallRejectWhenTypeOddWeekItemIsOccupied(){
        $data = $this->_mockData(
            ['weekday_index'=>1, 'time_slot_id'=>1, 'repeat_unit'=>GradeAndYearUtil::TYPE_EVERY_WEEK]
        ); // 获取 周一第一个时段, 每周重复的测试数据进行插入

        $dao = new TimetableItemDao();
        $item = $dao->createTimetableItem($data); // 插入到数据库中

        $this->assertNotNull($item);

        $testData = $this->_mockData(
            ['weekday_index'=>1, 'time_slot_id'=>1, 'repeat_unit'=>GradeAndYearUtil::TYPE_EVERY_ODD_WEEK]
        ); // 获取 周一第一个时段, 单周重复的测试数据

        $testDataEven = $this->_mockData(
            ['weekday_index'=>1, 'time_slot_id'=>1, 'repeat_unit'=>GradeAndYearUtil::TYPE_EVERY_EVEN_WEEK]
        ); // 获取 周一第一个时段, 双周重复的测试数据

        $testDataNa = $this->_mockData(
            ['weekday_index'=>1, 'time_slot_id'=>1, 'repeat_unit'=>1000]
        ); // 错误的数据, 直接 reject

        $result = $dao->hasAnyOneTakenThePlace($testData);
        $this->assertNotFalse($result);

        $result = $dao->hasAnyOneTakenThePlace($testDataEven);
        $this->assertNotFalse($result);

        $result = $dao->hasAnyOneTakenThePlace($testDataNa);
        $this->assertNotFalse($result);

        try{
            DB::table('timetable_items')->where('year','=',2000)->delete();
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
        }
    }

    // 情况3: 如果是 单周的要插入, 数据库中对应时段之后双周的, 那么可以通过检测
    public function testItCanPass(){
        $data = $this->_mockData(
            ['weekday_index'=>1, 'time_slot_id'=>1, 'repeat_unit'=>GradeAndYearUtil::TYPE_EVERY_ODD_WEEK]
        ); // 获取 周一第一个时段, 单周重复的测试数据进行插入

        $dao = new TimetableItemDao();
        $item = $dao->createTimetableItem($data); // 插入到数据库中

        $this->assertNotNull($item);

        $testData = $this->_mockData(
            ['weekday_index'=>1, 'time_slot_id'=>1, 'repeat_unit'=>GradeAndYearUtil::TYPE_EVERY_EVEN_WEEK]
        ); // 这是想插入一个同时同地的双周的

        $this->assertFalse($dao->hasAnyOneTakenThePlace($testData));

        // 但是 单周不允许单周的, 双周不允许双周的
        $this->assertNotFalse($dao->hasAnyOneTakenThePlace($data));

        try{
            DB::table('timetable_items')->where('year','=',2000)->delete();
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
        }

        // 反之一样可以插入
        $dao->createTimetableItem($testData);
        $this->assertFalse($dao->hasAnyOneTakenThePlace($data));

        // 但是 单周不允许单周的, 双周不允许双周的
        $this->assertNotFalse($dao->hasAnyOneTakenThePlace($testData));

        try{
            DB::table('timetable_items')->where('year','=',2000)->delete();
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
        }
    }

    private function _mockData($mock = []){
        $data = [
            'year'=>2000,
            'term'=>1,
            'course_id'=>1,
            'time_slot_id'=>1,
            'building_id'=>1,
            'room_id'=>1,
            'teacher_id'=>1,
            'grade_id'=>1,
            'weekday_index'=>1,
            'repeat_unit'=>GradeAndYearUtil::TYPE_EVERY_WEEK,
            'at_special_datetime'=>'2019-10-01',
            'to_special_datetime'=>'2019-10-01',
            'to_replace'=>0,
            'last_updated_by'=>1,
            'school_id'=>1,
            'published'=>false
        ];
        foreach ($mock as $key=>$value){
            $data[$key] = $value;
        }
        return $data;
    }
}