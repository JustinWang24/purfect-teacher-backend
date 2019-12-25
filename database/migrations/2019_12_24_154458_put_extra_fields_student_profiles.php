<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PutExtraFieldsStudentProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->boolean('create_file')->default(false)->comment('是否建档立卡');
            $table->boolean('special_support')->default(false)->comment('是否农村低保');
            $table->boolean('very_poor')->default(false)->comment('是否农村特困');
            $table->boolean('disability')->default(false)->comment('是否残疾');

            $table->string('resident_type',40)->nullable()->comment('户籍性质: 农业/非农业');
            $table->string('resident_suburb',40)->nullable()->comment('户籍所在乡镇');
            $table->string('resident_village',40)->nullable()->comment('户籍所在村');

            $table->text('comments')->nullable()->comment('备注');
            $table->string('avatar',255)->default(\App\User::DEFAULT_USER_AVATAR)->change();
            $table->string('parent_name',30)->nullable()->change();
            $table->string('parent_mobile',20)->nullable()->change();
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
            $table->dropColumn('create_file');
            $table->dropColumn('special_support');
            $table->dropColumn('very_poor');
            $table->dropColumn('disability');
            $table->dropColumn('resident_type');
            $table->dropColumn('resident_suburb');
            $table->dropColumn('resident_village');
            $table->dropColumn('comments');
        });
    }
}
