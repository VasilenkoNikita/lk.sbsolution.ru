<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->comment('ID проекта');
            $table->string('name', 255)->comment('Наименование доски');
            $table->string('description', 255)->nullable()->comment('Описание доски');
            $table->integer('position')->unsigned()->nullable()->comment('Позиция для сортировки');
            $table->integer('topics_count')->unsigned()->comment('Количество тем/проектов');
            $table->integer('messages_count')->unsigned()->comment('Количество соощений');
            $table->bigInteger('last_message_id')->unsigned()->nullable()->comment('ID последнего сообщения');

			//Ограничение внешнего ключа на ID проекта
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
        Schema::dropIfExists('boards');
    }
}
