<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsPatentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_patents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                  ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('patent_number')->unsigned()->nullable()->comment('Номер патента');
            $table->string('type_of_company', 512)->nullable()->comment('Вид деятельности');
            $table->string('patent_code', 512)->nullable()->comment('Идентификационный код');
            $table->string('point_address', 1024)->nullable()->comment('Адрес точки');
            $table->timestamp('patent_start_date')->nullable()->comment('Дата начала действия патента');
            $table->timestamp('patent_end_date')->nullable()->comment('Дата окончания действия патента');
            $table->timestamp('first_date_of_payment')->nullable()->comment('Дата оплаты первого платежа');
            $table->timestamp('second_date_of_payment')->nullable()->comment('Дата оплаты второго платежа');
            $table->string('patent_comment', 1024)->nullable()->comment('Адрес точки');
            $table->char('patent_status', 1)->default(1)->comment('Статус патента');
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
        Schema::dropIfExists('clients_patents');
    }
}
