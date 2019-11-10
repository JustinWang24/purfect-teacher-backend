<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWiifNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// wifi通知
        Schema::create('wifi_notices', function (Blueprint $table) {
            $table->bigIncrements('noticesid');
			$table->string('school_ids',2468)->nullable()->comment('多个学校逗号分隔');
            $table->string('notices_title')->nullable()->comment('通知内容');
            $table->tinyInteger('status')->default(1)->comment('状态(1:显示,0:不显示)');
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
        Schema::dropIfExists('wifi_notices');
    }
}
