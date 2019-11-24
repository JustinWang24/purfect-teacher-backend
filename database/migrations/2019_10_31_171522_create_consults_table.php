<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if(!Schema::hasTable('consults')) {
             Schema::create('consults', function (Blueprint $table) {
                 $table->increments('id');
                 $table->text('question')->comment('问题')->nullable();
                 $table->text('answer')->comment('答案')->nullable();
                 $table->integer('school_id')->comment('学校ID');
                 $table->integer('last_updated_by')->comment('最后修改人')->nullable();
                 $table->timestamps();
                 $table->softDeletes();
             });
         }
         DB::statement(" ALTER TABLE consults comment '咨询问题表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consults');
    }
}
