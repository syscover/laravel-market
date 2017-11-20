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
		if(! Schema::hasTable('market_tax_rule'))
		{
			Schema::create('market_tax_rule', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id');
				$table->string('name');
                $table->string('translation')->nullable();

				// si apunta a varias reglas, se establezce la prioridad de aplicación sobre el producto
				$table->smallInteger('priority')->unsigned();

				// en el caso de aplicar varios impuestos, el orden en el que aparecerá en el caso de haber varios impuestos
				$table->smallInteger('sort')->unsigned();

                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('market_tax_rule');
	}
}