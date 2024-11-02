<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('member_id')->unsigned()->comment('ID участника проекта');
            $table->bigInteger('role_id')->unsigned()->comment('ID роли участника проекта');
            $table->bigInteger('inherited_from')->unsigned()->nullable()->comment('ID пользователя');

			//Ограничение внешних ключей member_id, role_id, inherited_from
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('role_id')->references('id')->on('roles_projects');
            $table->foreign('inherited_from')->references('id')->on('member_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_roles');
    }
}
