<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('changeset_id')->unsigned();
            $table->string('action', 1);
            $table->string('path', 255);
            $table->string('form_path', 255)->nullable();
            $table->string('from_revision', 255)->nullable();
            $table->string('revision', 255)->nullable();
            $table->string('branch', 255)->nullable();

            $table->foreign('changeset_id')->references('id')->on('changesets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('changes');
    }
}
