<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id');
            $table->unsignedSmallInteger('posit')->comment('位置 1首页');
            $table->unsignedBigInteger('type')->comment('0:无跳转, 1:图文, 2:url');
            $table->tinyInteger('sort')->default(0)->comment('排序');
            $table->string('title', 100)->comment('标题');
            $table->string('image_url')->nullable()->comment('图片url');
            $table->string('content')->nullable()->comment('图文内容');
            $table->string('external')->nullable()->comment('外部URL');
            $table->unsignedSmallInteger('status')->default(1)->comment('状态 0:不显示, 1显示');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE banners comment 'banner表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
