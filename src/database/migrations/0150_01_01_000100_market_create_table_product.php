<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableProduct extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('market_product'))
		{
			Schema::create('market_product', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id');
                $table->string('sku', 50)->nullable();
				$table->integer('field_group_id')->unsigned()->nullable();

				// 1 - downloaded
				// 2 - transportable
				// 3 - downloaded and transportable
				$table->tinyInteger('type_id')->unsigned();

				// set parent product and config like subproduct
				$table->integer('parent_id')->unsigned()->nullable();

				$table->decimal('weight', 11, 3)->default(0);
				$table->boolean('active')->default(false);
				$table->integer('sort')->unsigned()->nullable();

				// 1 - single price
				// 2 - undefined price
				$table->tinyInteger('price_type_id')->unsigned(); // single price or undefined

				$table->decimal('subtotal', 12, 4)->nullable();

				// taxes
				$table->integer('product_class_tax_id')->unsigned()->nullable();

				$table->json('data_lang')->nullable();
				$table->json('data')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['sku'], 'ix01_product');
				$table->foreign('field_group_id', 'fk01_market_product')
					->references('id')
					->on('admin_field_group')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('product_class_tax_id', 'fk02_market_product')
					->references('id')
					->on('market_product_class_tax')
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
        Schema::dropIfExists('market_product');
	}
}