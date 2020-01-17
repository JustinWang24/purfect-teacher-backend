<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecuitNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruit_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('plan_id')->default(0)->comment('关联的招生简章');
            $table->text('content')->comment('报名须知的内容');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruit_notes');
    }
}
