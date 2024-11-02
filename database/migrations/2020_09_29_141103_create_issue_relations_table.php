<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('issue_from_id')->unsigned();
            $table->bigInteger('issue_to_id')->unsigned();
            $table->string('relation_type', 255);
            $table->integer('delay')->nullable();

			//Ограничение внешних ключей на issue_from_id и issue_to_id проекта
            $table->foreign('issue_from_id')->references('id')->on('issues');
            $table->foreign('issue_to_id')->references('id')->on('issues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issue_relations');
    }
}
