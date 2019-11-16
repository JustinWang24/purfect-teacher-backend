<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendMinAppliedInElectiveForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->unsignedSmallInteger('min_applied')->after('open_num')->default(0)->comment(
                '当报名人数超过这个值时自动开班. 默认 0, 表示此值无效'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->dropColumn('min_applied');
        });
    }
}
