<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
			$table->string('first_name')->comment('Имя пользователя');
            $table->string('last_name')->comment('Фамилия пользователя');
			$table->char('mail_notification', 1)->default(0)->comment('Подписка на email рассылку');
            $table->char('status', 1)->default(1)->comment('Статус активности пользователя');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
			$table->dropColumn('first_name');
            $table->dropColumn('last_name');
			$table->dropColumn('mail_notification');
            $table->dropColumn('status');
		});
    }
}
