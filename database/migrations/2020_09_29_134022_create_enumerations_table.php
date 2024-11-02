<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnumerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enumerations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->integer('position')->unsigned()->nullable();
            $table->integer('is_default');
            $table->integer('type')->nullable();
            $table->integer('active')->unsigned();
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();

			//Ограничение внешних ключей на project_id и parent_id проекта
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('parent_id')->references('id')->on('enumerations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enumerations');
    }
}
