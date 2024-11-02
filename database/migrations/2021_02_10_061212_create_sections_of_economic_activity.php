<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsOfEconomicActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        if (!Schema::hasTable('sections_of_economic_activity')) {
            Schema::create('sections_of_economic_activity', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('section_name', 512)->nullable()->comment('Наименование раздела');
                $table->string('section_code', 4)->nullable()->comment('Код раздела');
                $table->string('section_description', 512)->nullable()->comment('Описание раздела');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections_of_economic_activity');
    }
}
