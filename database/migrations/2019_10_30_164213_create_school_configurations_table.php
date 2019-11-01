<?php
/**
 * 针对于某个学校内的配置项的数据模型. 对学校的配置, 采用的策略是, 如果当前级别不存在, 则使用上级的, 而学校一级的, 为最终的默认配置
 * 学校一级的配置是最低优先级
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Utils\Misc\ConfigurationTool;

class CreateSchoolConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_configurations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedSmallInteger(ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR)
                ->default(1)->comment('本校学生每年可以选择的选修课数量');

            $table->boolean(ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION)
                ->default(false)->comment('自习课是否需要学生签到');
            $table->unsignedSmallInteger(ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM)
                ->default(ConfigurationTool::DEFAULT_STUDY_WEEKS_PER_TERM)->comment('学生每学期的教学周数');

            $table->timestamps();
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->unsignedSmallInteger(ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR)
                ->default(1)->comment('本系学生每年可以选择的选修课数量');

            $table->boolean(ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION)
                ->default(false)->comment('本系学生自习课是否需要学生签到');

            $table->unsignedSmallInteger(ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM)
                ->default(ConfigurationTool::DEFAULT_STUDY_WEEKS_PER_TERM)
                ->comment('本系学生学生每学期的教学周数');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_configurations');

        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(ConfigurationTool::KEY_OPTIONAL_COURSES_PER_YEAR);
            $table->dropColumn(ConfigurationTool::KEY_SELF_STUDY_NEED_REGISTRATION);
            $table->dropColumn(ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM);
        });
    }
}
