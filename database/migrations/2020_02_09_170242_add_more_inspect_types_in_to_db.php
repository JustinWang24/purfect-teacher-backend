<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreInspectTypesInToDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moreTypes = [
            '卫生',
            '考试',
            '班级',
            '纪律',
        ];
        foreach ($moreTypes as $moreType) {
            $found = \App\Models\Notices\NoticeInspect::where('name',$moreType)->first();
            if(!$found){
                \App\Models\Notices\NoticeInspect::create(
                    ['school_id'=>1,'name'=>$moreType]
                );
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

    }
}
