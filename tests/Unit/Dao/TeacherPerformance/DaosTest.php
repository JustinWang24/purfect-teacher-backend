<?php

namespace Tests\Unit\Dao\TeacherPerformance;

use App\Dao\Performance\TeacherPerformanceConfigDao;
use App\Dao\Schools\SchoolDao;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DaosTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSchoolCanLoadTeacherPerformanceConfigs()
    {
        $dao = new SchoolDao();
        $school = $dao->getSchoolById(1);
        $this->assertNotNull($school->teacherPerformanceConfigs);
    }

    public function testTeacherPerformanceConfigDaoCrud(){
        $dao = new TeacherPerformanceConfigDao();

        $config = $dao->create($this->mockConfigData());
        $this->assertNotNull($config);

        $id = $config->id;

        $updated = $dao->update($this->mockConfigData($id));
        $this->assertEquals($updated,1);

        $deleted = $dao->delete($config->id);
        $this->assertEquals($deleted, 1);
    }

    private function mockConfigData($withId = null){
        return [
            'school_id'=>1,
            'name'=>'name',
            'description'=>'description'. random_int(1, 100000),
            'id'=>$withId,
        ];
    }
}
