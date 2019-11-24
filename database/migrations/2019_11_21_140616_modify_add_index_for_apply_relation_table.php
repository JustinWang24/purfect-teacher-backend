<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAddIndexForApplyRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_apply_elective_courses', function (Blueprint $table) {
            $table->index('school_id', 'idx_taec_school_id');
            $table->index('teacher_id', 'idx_taec_teacher_id');
            $table->index(['start_year', 'term'], 'idx_taec_start_year_term');
            $table->index('created_at', 'idx_taec_created_at');
        });
        Schema::table('student_enrolled_optional_courses', function (Blueprint $table) {
            $table->index('course_id', 'idx_seoc_course_id');
            $table->index('user_id', 'idx_seoc_user_id');
            $table->index('created_at', 'idx_seoc_created_at');
        });
        Schema::table('course_electives', function (Blueprint $table) {
            $table->index('course_id', 'idx_ce_course_id');
            $table->index('created_at', 'idx_ce_created_at');
        });
        Schema::table('apply_course_arrangements', function (Blueprint $table) {
            $table->index('apply_id', 'idx_aca_apply_id');
        });
        Schema::table('apply_course_majors', function (Blueprint $table) {
            $table->index('apply_id', 'idx_acm_apply_id');
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
            $table->dropIndex('idx_taec_school_id');
            $table->dropIndex('idx_taec_teacher_id');
            $table->dropIndex('idx_taec_start_year_term');
            $table->dropIndex('idx_taec_created_at');
        });
        Schema::table('student_enrolled_optional_courses', function (Blueprint $table) {
            $table->dropIndex('idx_seoc_course_id');
            $table->dropIndex('idx_seoc_user_id');
            $table->dropIndex('idx_seoc_school_id');
            $table->dropIndex('idx_seoc_created_at');
        });
        Schema::table('course_electives', function (Blueprint $table) {
            $table->dropIndex('idx_ce_course_id');
            $table->dropIndex('idx_ce_created_at');
        });
        Schema::table('apply_course_arrangements', function (Blueprint $table) {
            $table->dropIndex('idx_aca_apply_id');
        });
        Schema::table('apply_course_majors', function (Blueprint $table) {
            $table->dropIndex('idx_acm_apply_id');
        });
    }
}
