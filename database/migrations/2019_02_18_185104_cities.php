<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities1', function($table) {

            $table->string('country');
            $table->string('city');
            $table->string('accent_city')->nullable();
            $table->string('region')->nullable();
            $table->unsignedInteger('population')->default(0);
            $table->string('latitude')->default(0);
            $table->string('longitude')->default(0);

            $table->index('city');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cities');
    }
}
