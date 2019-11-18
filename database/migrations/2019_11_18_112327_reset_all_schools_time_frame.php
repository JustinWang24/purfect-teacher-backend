<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\School;
use App\Models\Timetable\TimeSlot;

class ResetAllSchoolsTimeFrame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schools = School::all();

        $dao = new \App\Dao\Timetable\TimeSlotDao();
        $frames = $dao->getDefaultTimeFrame()['frames'];

        foreach ($schools as $school) {
            foreach ($frames as $frame) {
                $exist = TimeSlot::where('school_id',$school->id)
                    ->where('type',$frame['type'])
                    ->where('from',$frame['from'])
                    ->where('to',$frame['to'])
                    ->first();
                $frame['school_id'] = $school->id;
                if(!$exist){
                    $dao->createTimeSlot($frame);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
