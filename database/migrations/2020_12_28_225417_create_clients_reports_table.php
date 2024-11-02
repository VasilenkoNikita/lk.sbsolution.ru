<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('report_id')->unsigned()->comment('ID отчета')
                ->constrained('reports')->onUpdate('cascade')->onDelete('cascade');
            $table->string('comment', 1024)->nullable()->comment('Комментарий по отчету');
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
        Schema::dropIfExists('clients_reports');
    }
}
