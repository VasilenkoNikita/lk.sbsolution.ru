<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tracker_id')->unsigned()->nullable();
            $table->bigInteger('project_id')->unsigned();
            $table->string('subject', 255)->comment('Тема задачи');
            $table->string('description', 255)->nullable()->comment('Описание задачи');
            $table->date('due_date')->nullable()->comment('Срок выполнения задачи');
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('status_id')->unsigned();
            $table->bigInteger('assigned_to_id')->unsigned();
            $table->bigInteger('priority_id')->unsigned()->nullable();
            $table->bigInteger('fixed_version_id')->unsigned();
            $table->bigInteger('author_id')->unsigned();
            $table->integer('lock_version')->unsigned();
            $table->timestampTz('created_on')->nullable();
            $table->timestampTz('updated_on')->nullable();
            $table->date('start_date')->nullable()->comment('Дата начала выполнения задачи');
            $table->integer('done_ratio')->unsigned()->nullable()->comment('Процент выполнения задачи');
            $table->float('estimated_hours', 8)->nullable()->comment('Потраченное время на задачу');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->bigInteger('root_id')->unsigned()->nullable();
            $table->integer('lft')->unsigned()->nullable();
            $table->integer('rgt')->unsigned()->nullable();

            $table->foreign('tracker_id')->references('id')->on('trackers');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('category_id')->references('id')->on('issue_categories');
            $table->foreign('status_id')->references('id')->on('issue_statuses');
            $table->foreign('assigned_to_id')->references('id')->on('users');
            $table->foreign('priority_id')->references('id')->on('enumerations');
            $table->foreign('fixed_version_id')->references('id')->on('versions');
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('issues');
            $table->foreign('root_id')->references('id')->on('issues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
