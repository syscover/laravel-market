<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableTaxRulesCustomerClassTaxes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('market_tax_rules_customer_class_taxes'))
		{
			Schema::create('market_tax_rules_customer_class_taxes', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->integer('tax_rule_id')->unsigned();
				$table->integer('customer_class_tax_id')->unsigned();

				$table->primary(['tax_rule_id', 'customer_class_tax_id'], 'pk01_market_tax_rules_customer_class_taxes');
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
        Schema::dropIfExists('market_tax_rules_customer_class_taxes');
	}
}