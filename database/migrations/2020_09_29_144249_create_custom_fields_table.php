<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 30);
            $table->string('name', 30);
            $table->string('field_format', 30);
            $table->string('possible_values', 255);
            $table->string('regexp', 255)->nullable();
            $table->integer('min_length')->nullable();
            $table->integer('max_length');
            $table->char('is_required', 1);
            $table->char('is_for_all', 1);
            $table->char('is_filter', 1);
            $table->integer('position')->unsigned()->nullable();
            $table->char('searchable', 1)->nullable();
            $table->string('default_value', 255)->nullable();
            $table->char('editable', 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_fields');
    }
}
