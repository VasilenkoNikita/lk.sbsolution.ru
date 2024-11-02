<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('watchable_id')->unsigned()->comment('ID пользователя наблюдателя за задачей');
            $table->bigInteger('user_id')->unsigned()->comment('ID пользователя');
			
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
        Schema::dropIfExists('watchers');
    }
}
