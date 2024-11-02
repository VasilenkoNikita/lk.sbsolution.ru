<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsPlaceOfBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('clients_place_of_business')) {
            Schema::create('clients_place_of_business', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('client_id')->unsigned()->comment('ID клиента')
                    ->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
                $table->string('region', 512)->nullable()->comment('Регион ведения деятельности');
                $table->string('tax_registrar', 512)->nullable()->comment('Налоговая регистратор');
                $table->string('tax_registrar_code', 512)->nullable()->comment('Код налоговой');
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
        Schema::dropIfExists('clients_place_of_business');
    }
}
