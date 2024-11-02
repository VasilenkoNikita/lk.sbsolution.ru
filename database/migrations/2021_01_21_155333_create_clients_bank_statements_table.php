<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsBankStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('clients_bank_statements')) {
            Schema::create('clients_bank_statements', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                    ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
                $table->string('bank_name', 512)->nullable()->comment('Наименование банка');
                $table->string('account_type', 512)->nullable()->comment('Тип аккаунта');
                $table->bigInteger('checking_account')->unsigned()->nullable()->comment('Расчетный счет');
                $table->timestamp('bank_statement_processing_date')->nullable()->comment('Дата обработки');
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
        Schema::dropIfExists('clients_bank_statements');
    }
}
