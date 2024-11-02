<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('clients', function (Blueprint $table) {
			$table->timestamp('start_date')->nullable()->comment('Дата начала работы с клиентом');
			$table->timestamp('client_transfer_date')->nullable()->comment('Дата передачи клиента внутри фирмы');
            $table->string('primary_documents')->nullable()->comment('Первичные документы');
			$table->string('contracting_documents')->nullable()->comment('Документы для сторонних контрагентов');
			$table->string('features_of_the_type_of_accounting')->nullable()->comment('Особенности вида деятельности по учету в компании');
			$table->string('features_of_calculating_taxes')->nullable()->comment('Особенности расчета налогов');
			$table->string('preliminary_tax_calculation')->nullable()->comment('Предварительный расчет налога');
			$table->string('payment_procedure')->nullable()->comment('Порядок уплаты прибыли/ндс/усн/страх. взносов');
			$table->timestamp('salary_payment_date')->nullable()->comment('Дата выплаты зарплаты');
			$table->timestamp('advance_payment_date')->nullable()->comment('Дата выплаты аванса');
			$table->integer('number_of_employees')->nullable()->comment('Количество сотрудников');
			$table->string('comment_of_employees')->nullable()->comment('Комментарий к сотрудникам');
			$table->string('other_calculations_phys_clients')->nullable()->comment('Прочие расчеты с физ. лицами');
			$table->string('loans')->nullable()->comment('Займы/фин. помощь/лизинг');
			$table->string('bank_operations')->nullable()->comment('Банковские операции (выписки)');
			$table->string('cashbox')->nullable()->comment('Кассы');
			$table->string('additional_information')->nullable()->comment('Дополнительная информация о клиенте');
			$table->string('current_troubles')->nullable()->comment('Текущие проблемы');

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
			$table->dropColumn('start_date');
			$table->dropColumn('client_transfer_date');
            $table->dropColumn('primary_documents');
			$table->dropColumn('contracting_documents');
			$table->dropColumn('features_of_the_type_of_accounting');
			$table->dropColumn('features_of_calculating_taxes');
			$table->dropColumn('preliminary_tax_calculation');
			$table->dropColumn('payment_procedure');
			$table->dropColumn('salary_payment_date');
			$table->dropColumn('advance_payment_date');
			$table->dropColumn('number_of_employees');
			$table->dropColumn('comment_of_employees');
			$table->dropColumn('other_calculations_phys_clients');
			$table->dropColumn('loans');
			$table->dropColumn('bank_operations');
			$table->dropColumn('cashbox');
			$table->dropColumn('additional_information');
			$table->dropColumn('current_troubles');
        });
    }
}
