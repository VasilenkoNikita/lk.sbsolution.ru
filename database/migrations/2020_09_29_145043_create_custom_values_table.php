<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customized_types')->unsigned();
            $table->integer('customized_id')->unsigned();
            $table->bigInteger('custom_field_id')->unsigned();
            $table->integer('value')->unsigned()->nullable();

            $table->foreign('custom_field_id')->references('id')->on('custom_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_values');
    }
}
