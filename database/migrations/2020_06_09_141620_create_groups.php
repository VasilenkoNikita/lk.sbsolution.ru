<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID группы клиентов');
			$table->string('name')->nullable()->comment('Название группы клиентов');
            $table->string('description')->nullable()->comment('Дополнительная информация');
			$table->string('group_active', 1)->default('N')->nullable()->comment('Статус активности группы клиентов');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
