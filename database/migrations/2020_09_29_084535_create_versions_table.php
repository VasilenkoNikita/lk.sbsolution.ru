<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->comment('ID проекта');
            $table->string('name', 255)->comment('ID наименование новой версии проекта');
            $table->string('description', 255)->nullable()->comment('ID описание новой версии проекта');
            $table->date('effective_date')->nullable()->comment('Дата новой версии');
            $table->timestamp('created_on')->nullable();
            $table->timestamp('updated_on')->nullable();
            $table->string('status', 255)->nullable()->comment('Статус версии проекта');
            $table->string('sharing', 255)->comment('С чем связана новая версия проекта');

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
        Schema::dropIfExists('versions');
    }
}
