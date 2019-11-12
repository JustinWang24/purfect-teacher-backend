<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogoInSchoolConfigAndStudentProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->text('qr_code_url')->nullable()->comment('用户的永久二维码');
            $table->text('qr_code_url_tmp')->nullable()->comment('用户的临时二维码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn('qr_code_url');
            $table->dropColumn('qr_code_url_tmp');
        });
    }
}
