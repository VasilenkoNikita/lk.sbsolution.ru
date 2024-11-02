<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class   CreateChangesetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changesets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('repository_id')->unsigned();
            $table->string('revision', 255);
            $table->string('committer', 255)->nullable();
            $table->timestampTz('commited_on');
            $table->string('comments', 255)->nullable();
            $table->date('commit_date')->nullable();
            $table->string('scmid', 255)->nullable();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('repository_id')->references('id')->on('repositories');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('changesets');
    }
}
