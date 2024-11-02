<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangesetsIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changesets_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('changeset_id')->unsigned();
            $table->bigInteger('issue_id')->unsigned();

			//Ограничение внешних ключей на changeset_id и issue_id проекта
            $table->foreign('changeset_id')->references('id')->on('changesets');
            $table->foreign('issue_id')->references('id')->on('issues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('changesets_issues');
    }
}
