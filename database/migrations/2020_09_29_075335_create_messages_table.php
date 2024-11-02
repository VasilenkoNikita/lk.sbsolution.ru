<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('board_id')->unsigned()->comment('ID доски');
            $table->bigInteger('parent_id')->unsigned()->nullable()->comment('ID родительского сообщения');
            $table->string('subject', 255)->comment('ID тема сообщения');
            $table->string('content', 255)->comment('ID тело сообщения');
            $table->bigInteger('author_id')->unsigned()->comment('ID автора сообщения');
            $table->integer('replies_count')->unsigned()->comment('Количество ответов на сообщение');
            $table->bigInteger('last_reply_id')->unsigned()->nullable()->comment('ID последнего ответа на сообщение');
            $table->timestampTz('created_on')->nullable();
            $table->timestampTz('updated_on')->nullable();
            $table->char('locked', 1)->nullable()->comment('Сообщение закрыто/открыто для ответа');
            $table->integer('sticky')->nullable()->comment('Прикрепление к доске сообщений');

			//Ограничение внешних ключей parent_id, last_reply_id, author_id, board_id
            $table->foreign('parent_id')->references('id')->on('messages');
            $table->foreign('last_reply_id')->references('id')->on('messages');
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('board_id')->references('id')->on('boards');
        });
		
			//Ограничение внешнего ключа  last_message_id
        Schema::table('boards', function (Blueprint $table) {
            $table->foreign('last_message_id')->references('id')->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->dropForeign('boards_last_message_id_foreign');
        });
		
        Schema::dropIfExists('messages');
    }
}
