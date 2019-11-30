<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewsTypeInNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function(Blueprint $table){
            $table->unsignedSmallInteger('type')
                ->default(\App\Models\Schools\News::TYPE_NEWS)
                ->comment('文章类型');
            $table->json('tags')->nullable()->comment('文章的标签');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('tags');
        });
    }
}
