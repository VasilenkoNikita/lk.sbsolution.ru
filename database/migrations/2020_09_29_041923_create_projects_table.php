<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 30)->index()->comment('Наименование проекта');
            $table->string('description', 255)->nullable()->comment('Описание проекта');
            $table->string('homepage', 255)->nullable()->comment('Ссылка на страницу проекта');
            $table->char('is_public', 1)->comment('Публичная доступность проекта');
            $table->integer('parent_id')->unsigned()->nullable()->comment('ID родительского проекта');
            $table->timestampTz('created_on')->nullable();
            $table->timestampTz('updated_on')->nullable();
            $table->string('identifier', 20)->nullable()->comment('Идентификатор проекта');
            $table->integer('status')->unsigned()->default(1)->comment('Статус проекта');
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
