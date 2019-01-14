<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableTaxRulesTaxRatesZones extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('market_tax_rules_tax_rates_zones'))
		{
			Schema::create('market_tax_rules_tax_rates_zones', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->integer('tax_rule_id')->unsigned();
				$table->integer('tax_rate_zone_id')->unsigned();

				$table->primary(['tax_rule_id', 'tax_rate_zone_id'], 'pk01_market_tax_rules_tax_rates_zones');
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
        Schema::dropIfExists('market_tax_rules_tax_rates_zones');
	}
}