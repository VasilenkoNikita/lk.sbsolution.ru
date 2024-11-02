<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsReportingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_reporting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->string('event_name', 1024)->nullable()->comment('Имя события');
            $table->string('event_action', 1024)->nullable()->comment('Действие по событию');
            $table->timestampTz('report_date')->nullable()->comment('Дата события');
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
        Schema::dropIfExists('clients_reporting');
    }
}
