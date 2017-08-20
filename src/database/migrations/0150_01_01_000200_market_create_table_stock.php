<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableStock extends Migration
{
	public function up()
	{
		if (! Schema::hasTable('market_stock'))
		{
			Schema::create('market_stock', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id')->unsigned();
                $table->integer('warehouse_id')->unsigned();
                $table->integer('product_id')->unsigned();
                $table->decimal('stock', 11, 3)->default(0);
                $table->decimal('minimum_stock', 11, 3)->default(0);

				$table->foreign('warehouse_id', 'fk01_market_stock')
					->references('id')
					->on('market_warehouse')
					->onDelete('cascade')
					->onUpdate('cascade');

                $table->foreign('product_id', 'fk02_market_stock')
                    ->references('id')
                    ->on('market_product')
                    ->onDelete('cascade')
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
		Schema::dropIfExists('market_stock');
	}
}