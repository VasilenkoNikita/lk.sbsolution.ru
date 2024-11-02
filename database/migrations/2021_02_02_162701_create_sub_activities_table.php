<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sub_activities')) {
            Schema::create('sub_activities', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('economic_activity_id')->unsigned()->comment('ID класса ОКВЭД')
                    ->constrained('economic_activities')->onUpdate('cascade')->onDelete('cascade');
                $table->string('name', 512)->nullable()->comment('Название группировки класса ОКВЭД');
                $table->string('code', 512)->nullable()->comment('Код группировки класса ОКВЭД');
                $table->string('description', 512)->nullable()->comment('Описание группировки');
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
        Schema::dropIfExists('sub_activities');
    }
}
