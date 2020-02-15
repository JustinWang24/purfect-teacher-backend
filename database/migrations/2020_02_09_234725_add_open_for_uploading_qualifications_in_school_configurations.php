<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpenForUploadingQualificationsInSchoolConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_configurations', function (Blueprint $table) {
            $table->boolean('open_for_uploading_qualification')
                ->default(false)
                ->comment('开放系统，让老师可以上传佐证材料');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_configurations', function (Blueprint $table) {
            $table->dropColumn('open_for_uploading_qualification');
        });
    }
}
