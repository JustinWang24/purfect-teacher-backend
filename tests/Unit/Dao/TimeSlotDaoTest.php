<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 24/10/19
 * Time: 5:07 PM
 */

namespace Tests\Unit\Dao;

use Tests\TestCase;
use App\Dao\Timetable\TimeSlotDao;

class TimeSlotDaoTest extends TestCase
{

    public function testItCanGetTimeSlotsBySchool(){
        $dao = new TimeSlotDao();
        $result = $dao->getDefaultTimeFrame();
        $this->assertIsArray($result);
    }
}