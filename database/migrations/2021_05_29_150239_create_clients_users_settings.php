<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsUsersSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_users_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->unsigned()->comment('ID пользователя')
                ->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('row_name', 512)->nullable()->comment('Поле таблицы');
            $table->integer('position')->nullable()->comment('Позиция поля');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients_users_settings');
    }
}
