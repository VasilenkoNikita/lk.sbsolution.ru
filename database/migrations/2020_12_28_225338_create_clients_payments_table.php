<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('payment_id')->unsigned()->comment('ID оплаты')
                ->constrained('payments')->onUpdate('cascade')->onDelete('cascade');
            $table->string('comment', 1024)->nullable()->comment('Комментарий по оплате');
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
        Schema::dropIfExists('clients_payments');
    }
}
