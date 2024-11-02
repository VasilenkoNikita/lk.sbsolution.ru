<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queries', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->comment('ID проекта');
            $table->string('name', 255)->comment('ID наименование запроса');
            $table->string('filters', 255)->nullable()->comment('Фильтр запроса');
            $table->bigInteger('user_id')->unsigned();
            $table->char('is_public', 1)->comment('Публичная доступность запроса');
            $table->string('column_names', 255)->nullable()->comment('Колонки участвующие в запросе');
            $table->string('sort_criteria', 255)->nullable()->comment('Сортировка запроса');
            $table->string('group_by', 255)->nullable()->comment('Группировка запроса');
            $table->timestampTz('created_on');
            $table->timestampTz('updated_on');

			//Ограничени я внешних ключей project_id и user_id
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queries');
    }
}
