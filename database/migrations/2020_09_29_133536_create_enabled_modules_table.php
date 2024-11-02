<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnabledModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enabled_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('name', 255);
            $table->bigInteger('project_id')->unsigned()->comment('ID проекта');

			//Ограничение внешнего ключа на project_id проекта
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enabled_modules');
    }
}
