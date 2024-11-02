<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields_trackers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('custom_field_id')->unsigned();
            $table->bigInteger('tracker_id')->unsigned();

            $table->foreign('custom_field_id')->references('id')->on('custom_fields');
            $table->foreign('tracker_id')->references('id')->on('trackers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_fields_trackers');
    }
}
