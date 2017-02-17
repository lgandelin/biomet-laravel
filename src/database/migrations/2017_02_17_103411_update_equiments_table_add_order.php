<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEquimentsTableAddOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->integer('order')->after('hours_functionning')->nullable();
            $table->dropColumn('hours_functionning');
            $table->double('partial_counter', 8, 2)->after('tag')->nullable();
            $table->double('total_counter', 8, 2)->after('partial_counter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->double('hours_functionning', 8, 2)->after('tag')->nullable();
            $table->dropColumn('partial_counter');
            $table->dropColumn('total_counter');
        });
    }
}
