<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tracker_id')->unsigned()->comment('ID трекера');
            $table->bigInteger('old_status_id')->comment('ID старого статуса');
            $table->bigInteger('new_status_id')->comment('ID нового статуса');
            $table->bigInteger('role_id')->unsigned()->comment('ID роли');

			//Ограничение внешних ключей на tracker_id и role_id проекта
            $table->foreign('tracker_id')->references('id')->on('trackers');
            $table->foreign('role_id')->references('id')->on('roles_projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflows');
    }
}
