<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableGroupCustomerClassTax extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('group_customer_class_tax'))
		{
			Schema::create('group_customer_class_tax', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->integer('group_id')->unsigned();
				$table->integer('customer_class_tax_id')->unsigned();
				
				$table->foreign('group_id', 'fk01_group_customer_class_tax')
					->references('id_300')
					->on('group')
					->onDelete('cascade')
					->onUpdate('cascade');
				$table->foreign('customer_class_tax_id', 'fk02_group_customer_class_tax')
					->references('id_100')
					->on('customer_class_tax')
					->onDelete('cascade')
					->onUpdate('cascade');

				$table->primary('group_id', 'pk01_group_customer_class_tax');
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
		if(Schema::hasTable('group_customer_class_tax'))
		{
			Schema::drop('group_customer_class_tax');
		}
	}
}