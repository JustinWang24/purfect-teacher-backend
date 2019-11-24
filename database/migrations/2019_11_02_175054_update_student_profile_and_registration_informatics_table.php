<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStudentProfileAndRegistrationInformaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            // 报名信息
            $table->string('source_place')->nullable()->comment('生源地')->change();
            $table->string('detailed_address')->nullable()->comment('详细地址');
        });

        Schema::table('registration_informatics', function (Blueprint $table) {
            // 报名信息
            $table->string('note')->nullable()->comment('备注');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
