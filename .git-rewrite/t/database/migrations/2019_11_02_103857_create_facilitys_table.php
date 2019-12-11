<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('facilitys')) {

            Schema::create('facilitys', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('facility_number',50)->comment('设备编号');
                $table->string('facility_name',50)->comment('设备名称');
                $table->integer('school_id')->comment('学校ID');
                $table->integer('campus_id')->comment('校区ID');
                $table->integer('building_id')->comment('建筑ID');
                $table->integer('room_id')->comment('房间ID');
                $table->string('detail_addr')->comment('详细地点')->nullable();
                $table->tinyInteger('type')->comment('类型 1:监控 2:门禁 3:班排 4:教室设备')->nullable();
                $table->tinyInteger('status')->default(0)->comment('状态 0:关闭 1:开启');
                $table->timestamps();
                $table->softDeletes();
            });
        }
        DB::statement(" ALTER TABLE facilitys comment '设备表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilitys');
    }
}
