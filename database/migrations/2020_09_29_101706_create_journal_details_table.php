<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('journal_id')->unsigned()->comment('ID журнала');
            $table->string('property', 30);
            $table->string('prop_key', 30);
            $table->string('old_value', 255)->nullable();
            $table->string('value', 255)->nullable();
			
			//Ограничение внешнего ключа на journal_id проекта
            $table->foreign('journal_id')->references('id')->on('journals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal_details');
    }
}
