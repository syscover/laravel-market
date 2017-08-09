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
		if(! Schema::hasTable('market_group_customer_class_tax'))
		{
			Schema::create('market_group_customer_class_tax', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->integer('group_id')->unsigned();
				$table->integer('customer_class_tax_id')->unsigned();
				
				$table->foreign('group_id', 'fk01_market_group_customer_class_tax')
					->references('id')
					->on('group')
					->onDelete('cascade')
					->onUpdate('cascade');
				$table->foreign('customer_class_tax_id', 'fk02_market_group_customer_class_tax')
					->references('id')
					->on('customer_class_tax')
					->onDelete('cascade')
					->onUpdate('cascade');

				$table->primary('group_id', 'pk01_market_group_customer_class_tax');
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
	    Schema::dropIfExists('market_group_customer_class_tax');
	}
}