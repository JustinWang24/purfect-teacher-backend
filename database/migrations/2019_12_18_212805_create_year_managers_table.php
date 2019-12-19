<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('year')->comment('年级');
            $table->unsignedBigInteger('user_id')->comment('关联的用户');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('year_managers');
    }
}
