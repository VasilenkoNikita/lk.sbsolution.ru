<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsefulAccountingResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('useful_accounting_resources')) {
            Schema::create('useful_accounting_resources', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('resource_name', 512)->comment('Имя ресурса');
                $table->string('resource_link', 512)->comment('Ссылка на ресурс');
                $table->string('resource_status', 1024)->nullable()->comment('Комментарий по ресурсу');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('useful_accounting_resources');
    }
}
