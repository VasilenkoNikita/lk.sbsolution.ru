<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTypeOfTax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_type_of_tax', function (Blueprint $table) {
            $table->integer('client_id')->comment('ID клиента');
			$table->integer('type_of_tax_id')->comment('ID типа налогов');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients_type_of_tax');
    }
}
