<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEconomicActivityFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('economic_activities', function (Blueprint $table) {
            $table->string('section')->nullable()->comment('Раздел');
            $table->string('section_description')->nullable()->comment('Описание раздела');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('economic_activities', function (Blueprint $table) {
            $table->dropColumn('section');
            $table->dropColumn('section_description');
        });
    }
}
