<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_categories', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID категории задач');
            $table->bigInteger('project_id')->unsigned()->comment('ID проекта');
            $table->string('name', 30)->comment('Наименование категории задач');
            $table->bigInteger('assigned_to_id')->unsigned()->comment('Доступность категории задач указанным пользователям');

			//Ограничение внешних ключей на project_id и assigned_to_id проекта
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('assigned_to_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issue_categories');
    }
}
