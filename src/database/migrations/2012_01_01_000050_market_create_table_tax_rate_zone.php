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
		if(! Schema::hasTable('tax_rate_zone'))
		{
			Schema::create('tax_rate_zone', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id')->unsigned();
				$table->string('name');
				$table->string('country_id', 2);
				$table->string('territorial_area_1_id', 6)->nullable();
				$table->string('territorial_area_2_id', 10)->nullable();
				$table->string('territorial_area_3_id', 10)->nullable();
				$table->string('cp')->nullable();
                $table->decimal('tax_rate', 10, 2)->default(0);

                $table->timestamps();
                $table->softDeletes();

				$table->foreign('country_id', 'fk01_tax_rate_zone')
					->references('id')
					->on('country')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_1_id', 'fk02_tax_rate_zone')
					->references('id')
					->on('territorial_area_1')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_2_id', 'fk03_tax_rate_zone')
					->references('id')
					->on('territorial_area_2')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_3_id', 'fk04_tax_rate_zone')
					->references('id')
					->on('territorial_area_3')
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
        Schema::dropIfExists('tax_rate_zone');
	}
}