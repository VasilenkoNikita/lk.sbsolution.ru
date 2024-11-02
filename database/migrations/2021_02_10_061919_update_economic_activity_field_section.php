<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEconomicActivityFieldSection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('economic_activities', function (Blueprint $table) {
            $table->foreignId('section_economic_activity_id')->unsigned()->comment('ID раздела ОКВЭД')
                ->constrained('sections_of_economic_activity')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropColumn('section_economic_activity_id');
        });
    }
}
