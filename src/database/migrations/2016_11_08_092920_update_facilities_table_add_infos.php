<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFacilitiesTableAddInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('client_id')->after('name')->nullable();
            $table->double('longitude', 10, 6)->after('client_id')->nullable();
            $table->double('latitude', 10, 6)->after('longitude')->nullable();
            $table->text('address')->after('latitude')->nullable();
            $table->string('city')->after('address')->nullable();
            $table->string('department')->after('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facilities', function ($table) {
            $table->dropColumn('client_id');
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('department');
        });
    }
}
