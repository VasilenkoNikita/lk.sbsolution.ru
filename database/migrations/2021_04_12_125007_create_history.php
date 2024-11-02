<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('history')) {
            Schema::create('history', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('reference_table')->comment('Изменяемая таблица');
                $table->integer('reference_id')->unsigned()->comment('ID записи');
                $table->integer('user_id')->nullable()->comment('ID пользователя осуществившего правки');
                $table->string('change_type')->nullable()->comment('Тип изменения (CRUD)');
                $table->string('body')->nullable()->comment('Изменение');
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
        Schema::dropIfExists('history');
    }
}
