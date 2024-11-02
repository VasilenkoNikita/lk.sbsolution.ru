<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('commented_type', 30)->comment('Тип комментария');
            $table->bigInteger('commented_id')->unsigned()->comment('ID комментария');
            $table->bigInteger('author_id')->unsigned()->comment('ID автора комментария');
            $table->string('comments', 255)->nullable()->comment('Комментарии');
            $table->timestamp('created_on')->nullable();
            $table->timestamp('updated_on')->nullable();
			
			//Ограничение внешнего ключа на author_id проекта
            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
