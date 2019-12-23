<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Schools\Facility;

class AppendLocationToFacilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facilitys', function (Blueprint $table) {
            $table->unsignedSmallInteger('location')->default(Facility::LOCATION_INDOOR)->comment('室内或室外');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facilitys', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
}
