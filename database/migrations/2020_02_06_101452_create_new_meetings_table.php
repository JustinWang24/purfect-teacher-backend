<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('会议创建人');
            $table->integer('approve_userid')->comment('负责人');
            $table->integer('school_id')->comment('学校ID');
            $table->string('meet_title')->comment('标题');
            $table->text('meet_content')->comment('内容');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_meetings');
    }
}
