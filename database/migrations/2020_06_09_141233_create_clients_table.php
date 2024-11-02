<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID клиента');
			$table->string('type_of_ownership')->nullable()->comment('Форма собственности');
			$table->string('name')->nullable()->comment('Имя клиента');
			$table->string('organization')->nullable()->comment('Название организации');
			$table->string('phone')->nullable()->comment('Номер телефона');
			$table->string('email')->nullable()->comment('Email');
			$table->string('tax_system')->nullable()->comment('Система налогооблажения');
            $table->string('description')->nullable()->comment('Дополнительная информация');
			$table->string('client_active', 1)->default('N')->nullable()->comment('Статус активности клиента');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
