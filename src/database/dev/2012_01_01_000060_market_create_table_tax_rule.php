<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableTaxRule extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(! Schema::hasTable('tax_rule'))
		{
			Schema::create('tax_rule', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id')->unsigned();
				$table->string('name');
                $table->string('translation')->nullable();

				// si apunta a varias reglas, se establezce la prioridad de aplicación sobre el producto
				$table->smallInteger('priority')->unsigned();

				// en el caso de aplicar varios impuestos, el orden en el que aparecerá en el caso de haber varios impuestos
				$table->smallInteger('sort_order')->unsigned();
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
        Schema::dropIfExists('tax_rule');
	}
}