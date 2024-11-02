<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesOfTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_of_taxes', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID типа налогов');
			$table->string('name')->nullable()->comment('Наименование типа налога');
            $table->string('description')->nullable()->comment('Описание типа налога');
			$table->string('types_of_tax_active', 1)->default('N')->nullable()->comment('Статус активности типа налога');
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
        Schema::dropIfExists('types_of_taxes');
    }
}
