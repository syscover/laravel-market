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

                // set parent product and config like subproduct
                $table->integer('parent_id')->unsigned()->nullable();

				// marketable fields
                $table->string('object_type')->nullable();
                $table->integer('object_id')->unsigned()->nullable();

				// 1 - downloaded
				// 2 - transportable
				// 3 - downloaded and transportable
                // 4 - service
                // 5 - event
				$table->tinyInteger('type_id')->unsigned();

                // schedule limit time of publish product
                $table->timestamp('enable_from')->nullable();
                $table->timestamp('enable_to')->nullable();

                // events
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->integer('limited_capacity')->nullable();
                $table->decimal('fixed_cost', 12, 4)->nullable();
                $table->decimal('cost_per_sale', 12, 4)->nullable();

                // transportable product
				$table->decimal('weight', 11, 3)->default(0);

				// common properties
				$table->boolean('active')->default(false);
				$table->integer('sort')->unsigned()->nullable();

                // 1 - single price
                // 2 - undefined price
                $table->tinyInteger('price_type_id')->unsigned(); // single price or undefined

				// taxes
				$table->integer('product_class_tax_id')->unsigned()->nullable();

				// amounts
                $table->decimal('cost', 12, 4)->nullable();
                $table->decimal('subtotal', 12, 4)->nullable();

				$table->json('data_lang')->nullable();
				$table->json('data')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['sku'], 'ix01_market_product');
                $table->index(['object_type', 'object_id'], 'ix02_market_product');

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