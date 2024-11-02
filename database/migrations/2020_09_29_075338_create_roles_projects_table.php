<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 30)->nullable()->comment('Должность');
            $table->string('position', 10)->nullable()->comment('Позиция роли');
            $table->char('assignable', 1)->nullable()->comment('Назначаемая роль');
            $table->integer('builtin')->comment('Встроенная роль');
            $table->string('permissions', 255)->nullable()->comment('Права роли');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_projects');
    }
}
