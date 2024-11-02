<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('journalized_id')->unsigned();
            $table->string('journalized_type', 30)->comment('Тип журнала');
            $table->bigInteger('user_id')->unsigned()->comment('ID пользователч');
            $table->string('notes', 255)->nullable()->comment('Записи');
            $table->timestamp('created_on')->nullable();

			//Ограничение внешнего ключа на user_id проекта
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
        Schema::dropIfExists('journals');
    }
}
