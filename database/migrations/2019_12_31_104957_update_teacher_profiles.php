<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTeacherProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_profiles', function (Blueprint $table){
            $table->unsignedTinyInteger('category_teach')->nullable(true)->comment('授课类别：1文化课教师, 2公共课教师, 3专业课教师');
            $table->unsignedTinyInteger('category_major')->nullable(true)->comment('教师档案需要标注教师的职业授课类别: 1交通运输, 2农林牧渔, 3旅游服务, 4土木水利, 5文化教育, 6信息技术, 7财经商贸, 8医药卫生');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            if(Schema::hasColumn('teacher_profiles','category_teach')){
                $table->dropColumn('category_teach');
            }
            if(Schema::hasColumn('teacher_profiles','category_major')){
                $table->dropColumn('category_major');
            }
        });
    }
}
