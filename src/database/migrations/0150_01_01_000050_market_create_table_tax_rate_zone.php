<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableTaxRateZone extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('market_tax_rate_zone'))
		{
			Schema::create('market_tax_rate_zone', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id');
				$table->string('name');
				$table->string('country_id', 2);
				$table->string('territorial_area_1_id', 6)->nullable();
				$table->string('territorial_area_2_id', 10)->nullable();
				$table->string('territorial_area_3_id', 10)->nullable();
				$table->string('zip')->nullable();
                $table->decimal('tax_rate', 10, 2)->default(0);

                $table->timestamps();
                $table->softDeletes();

				$table->foreign('country_id', 'fk01_market_tax_rate_zone')
					->references('id')
					->on('admin_country')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_1_id', 'fk02_market_tax_rate_zone')
					->references('id')
					->on('admin_territorial_area_1')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_2_id', 'fk03_market_tax_rate_zone')
					->references('id')
					->on('admin_territorial_area_2')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_3_id', 'fk04_market_tax_rate_zone')
					->references('id')
					->on('admin_territorial_area_3')
					->onDelete('restrict')
					->onUpdate('cascade');
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
        Schema::dropIfExists('market_tax_rate_zone');
	}
}