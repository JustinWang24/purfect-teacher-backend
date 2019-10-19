<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('schools')) {

            Schema::create('schools', function (Blueprint $table) {
                $table->integerIncrements('id');
                $table->uuid('uuid')->index();
                $table->unsignedMediumInteger('max_students_number')->default(0)->comment('该学校所支持的最大学生账户数, 超过次数量则不予开放新学生账号注册功能. 0 表示无限制');
                $table->unsignedMediumInteger('max_employees_number')->default(0)->comment('该学校所支持的最大教职工账户数, 超过次数量则不予开放教职工账号注册功能. 0 表示无限制');
                $table->string('name', 100)->comment('学校名称');
                $table->string('logo', 200)->comment('学校logo')->nullable();;
                $table->string('video', 200)->comment('学校视频')->nullable();;
                $table->string('motto', 100)->comment('校训')->nullable();;
                $table->unsignedBigInteger('last_updated_by')->default(0)->comment('最后更新该学校的信息的用户');
                $table->timestamps();
                $table->softDeletes();

            });

            DB::statement(" ALTER TABLE schools comment '学校表' ");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
