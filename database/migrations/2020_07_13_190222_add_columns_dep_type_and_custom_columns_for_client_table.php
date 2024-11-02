<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsDepTypeAndCustomColumnsForClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('clients', function (Blueprint $table) {
            $table->string('type_of_company')->nullable()->comment('Вид деятельности');
			$table->string('region')->nullable()->comment('Регион');
			$table->string('reporting_system')->nullable()->comment('Система сдачи отчетности');
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
            $table->dropColumn('type_of_company');
			$table->dropColumn('region');
			$table->dropColumn('reporting_system');
        });
    }
}
