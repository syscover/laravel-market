<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableProductsCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('products_categories')) 
		{
			Schema::create('products_categories', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				
				$table->integer('product_id')->unsigned();
				$table->integer('category_id')->unsigned();

				$table->primary(['product_id', 'category_id'], 'pk01_products_categories');
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
		Schema::dropIfExists('products_categories');
	}
}