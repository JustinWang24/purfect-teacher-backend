<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('关联的学校');

            $table->unsignedSmallInteger('type')
                ->default(\App\Models\Schools\News::TYPE_NEWS)
                ->comment('类型');

            $table->boolean('publish')->default(false)->comment('是否发布');
            $table->string('title',255)->comment('动态标题');
            $table->timestamps();
        });

        Schema::create('news_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('news_id')->comment('关联的新闻');
            $table->unsignedMediumInteger('position')->default(1)->comment('段落的排序');
            $table->unsignedBigInteger('media_id')->nullable()->comment('段落的的图片或视频内容');
            $table->text('content')->nullable()->comment('段落的文字内容');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_sections');
    }
}
