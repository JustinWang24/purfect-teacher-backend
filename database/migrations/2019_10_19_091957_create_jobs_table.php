<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('jobs')){
            Schema::create('jobs', function (Blueprint $table){

            $table->integerIncrements('id');
            $table->uuid('uuid')->index();
            // 该专业关联的学校
            $table->unsignedSmallInteger('type')->comment('关联的学校');
            $table->json('payload_data');
            $table->boolean('complete')->default(false);
            $table->text('result')->nullable();
            $table->timestamps();
            });

            DB::statement(" ALTER TABLE jobs comment '耗时任务的临时表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
