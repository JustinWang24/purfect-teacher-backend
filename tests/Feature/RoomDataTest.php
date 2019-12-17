<?php

namespace Tests\Feature;

use App\Dao\Schools\BuildingDao;
use App\Dao\Schools\RoomDao;
use App\Models\Schools\Building;
use App\Models\Schools\Room;
use Illuminate\Support\Facades\DB;

class RoomDataTest extends BasicPageTestCase
{

    public function _CreateBuild() {
        $buildDao = new BuildingDao();
        for ($i = 1; $i<= 2; $i++) {
            $data = [
                'school_id' => 1,
                'campus_id' => 1,
                'name'      => $i.'号公寓',
                'type'      => Building::TYPE_STUDENT_HOSTEL_BUILDING,
                'description' => '宿舍楼',
            ];
            try {
                DB::beginTransaction();
                $re = $buildDao->createBuilding($data);
                $buildId[] = $re->id;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $msg = $e->getMessage();
            }
        }

        return $buildId;


    }


    /**
     * 创建学生宿舍
     */
    public function testCreateRoomData() {
        $buildId = $this->_CreateBuild();
        $roomDao = new RoomDao();
        try {
            DB::beginTransaction();
            foreach ($buildId as $key => $val){
                for ($i = 1; $i < 6; $i++) {
                    for ($k = 1; $k < 21; $k++) {
                        if ($k < 10) {
                            $k = '0' . $k;
                        }

                        $name = $i . $k;

                        // 2号楼1层
                        if($key == 1 && $i == 1) {

                            $description = '厨师宿舍';
                            if($name == 07) {
                                $description = '宿舍值班室';
                            }

                            $data = [
                                'school_id' => 1,
                                'campus_id' => 1,
                                'building_id' => $val,
                                'name' => '厨师宿舍'.$name,
                                'type' => Room::TYPE_STUDENT_HOSTEL,
                                'seats' => '10',
                                'description' => $description,
                            ];

                        } else {
                            $data = [
                                'school_id' => 1,
                                'campus_id' => 1,
                                'building_id' => $val,
                                'name' => $name,
                                'type' => Room::TYPE_STUDENT_HOSTEL,
                                'seats' => '10',
                                'description' => $this->description($name),
                            ];
                        }


                        $roomDao->createRoom($data);
                }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
        }

    }

    public function description($name) {
        $description = '学生宿舍';
        switch ($name) {
            case 101 : return $description = '办公室'; break;
            case 102 : return $description = '宿管科'; break;
            case 201 : return $description = '库房'; break;
            default : return $description;
        }

    }
}
