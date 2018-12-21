<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableTaxRulesProductClassTaxes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('market_tax_rules_product_class_taxes'))
		{
			Schema::create('market_tax_rules_product_class_taxes', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->integer('tax_rule_id')->unsigned();
				$table->integer('product_class_tax_id')->unsigned();

				$table->primary(['tax_rule_id', 'product_class_tax_id']);
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
        Schema::dropIfExists('market_tax_rules_product_class_taxes');
	}
}