<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableWarehouse extends Migration
{
	public function up()
	{
		if (! Schema::hasTable('warehouse'))
		{
			Schema::create('warehouse', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id')->unsigned();
				$table->string('name');

				// geolocation data
				$table->string('country_id', 2)->nullable();
				$table->string('territorial_area_1_id', 6)->nullable();
				$table->string('territorial_area_2_id', 10)->nullable();
				$table->string('territorial_area_3_id', 10)->nullable();
				$table->string('cp')->nullable();
				$table->string('locality')->nullable();
				$table->string('address')->nullable();
				$table->string('latitude')->nullable();
				$table->string('longitude')->nullable();
				$table->boolean('active');

				$table->foreign('country_id', 'fk01_warehouse')
					->references('id')
					->on('country')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_1_id', 'fk02_warehouse')
					->references('id')
					->on('territorial_area_1')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_2_id', 'fk03_warehouse')
					->references('id')
					->on('territorial_area_2')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('territorial_area_3_id', 'fk04_warehouse')
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
		Schema::dropIfExists('warehouse');
	}
}