<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('clients_accesses')) {
            Schema::create('clients_accesses', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                    ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
                $table->string('service_name', 512)->nullable()->comment('Наименование сервиса');
                $table->string('service_login', 512)->nullable()->comment('Логин');
                $table->string('service_password', 512)->nullable()->comment('Пароль');
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
        Schema::dropIfExists('clients_accesses');
    }
}
