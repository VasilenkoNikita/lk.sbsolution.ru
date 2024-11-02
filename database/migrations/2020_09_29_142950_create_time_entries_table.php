<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('issue_id')->unsigned();
            $table->float('hour', 8);
            $table->string('comments', 255)->nullable();
            $table->bigInteger('activity_id')->unsigned();
            $table->date('spent_on');
            $table->integer('tyear')->unsigned();
            $table->integer('tmonth')->unsigned();
            $table->integer('tweek')->unsigned();
            $table->timestampTz('created_on')->nullable();
            $table->timestampTz('updated_on')->nullable();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('issue_id')->references('id')->on('issues');
            $table->foreign('activity_id')->references('id')->on('enumerations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_entries');
    }
}
