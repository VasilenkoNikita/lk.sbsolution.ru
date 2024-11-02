<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsCashDesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('clients_cash_desks')) {
            Schema::create('clients_cash_desks', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                    ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
                $table->string('name_cash_desks', 512)->nullable()->comment('Наименование точки/кассы');
                $table->timestamp('date_of_cash_processing')->nullable()->comment('Дата обработки наличных');
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
        Schema::dropIfExists('clients_cash_desks');
    }
}
