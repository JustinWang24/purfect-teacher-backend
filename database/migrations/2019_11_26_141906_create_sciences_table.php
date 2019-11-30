<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSciencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sciences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id')->comment('学校ID');
            $table->string('title')->comment('科研成果');
            $table->text('content')->nullable()->comment('内容');
            $table->integer('media_id')->comment('文件ID')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
        DB::statement(" ALTER TABLE sciences comment '科技表' ");

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sciences');
    }
}
