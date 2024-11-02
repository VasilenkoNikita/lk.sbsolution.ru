<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToClientsMarketplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients_marketplaces', function (Blueprint $table) {
            $table->tinyInteger('activity')->nullable()->comment('Активность маркетплейса');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients_marketplaces', function (Blueprint $table) {
            $table->dropColumn('activity');
        });
    }
}
