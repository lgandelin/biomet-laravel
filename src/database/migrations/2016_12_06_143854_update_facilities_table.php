<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('country')->after('department')->nullable();
            $table->string('technology')->after('country')->nullable();
            $table->string('serial_number')->after('technology')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('technology');
            $table->dropColumn('serial_number');
        });
    }
}
