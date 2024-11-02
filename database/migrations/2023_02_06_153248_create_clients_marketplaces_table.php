<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsMarketplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('clients_marketplaces')) {
            Schema::create('clients_marketplaces', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                    ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
                $table->string('marketplace_name', 512)->nullable()->comment('Наименование маркетплейса');
                $table->timestamp('marketplace_processing_date')->nullable()->comment('Дата обработки');
                $table->string('comment', 2048)->nullable()->comment('Комментарий');
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
        Schema::dropIfExists('clients_marketplaces');
    }
}
