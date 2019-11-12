<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWifiUserAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// wifi用户协议同意表
		if(!Schema::hasTable('wifi_user_agreements')){
			Schema::create('wifi_user_agreements', function (Blueprint $table) {
				$table->bigIncrements('agreementid');
				$table->unsignedMediumInteger('user_id')->comment('用户id');
				$table->dateTime('created_at')->nullable(true)->comment('添加时间');
				//$table->softDeletes();
			});
		}
        DB::statement(" ALTER TABLE wifi_user_agreements comment 'wifi用户协议同意表' ");

    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wifi_user_agreements');
    }
}
