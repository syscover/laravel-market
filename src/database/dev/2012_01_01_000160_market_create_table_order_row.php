<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableOrderRow extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('order_row'))
		{
			Schema::create('order_row', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id')->unsigned();
				$table->string('lang_id', 2);
				$table->integer('order_id')->unsigned();
				$table->integer('product_id')->nullable()->unsigned();

				$table->string('name')->nullable();
				$table->text('description')->nullable();
				$table->json('data')->nullable();

				// amounts
				$table->decimal('price', 12, 4); 								        // unit price
				$table->decimal('quantity', 12, 4); 							        // number of units
				$table->decimal('subtotal', 12, 4);								        // subtotal without tax
                $table->decimal('total_without_discounts', 12, 4);                      // total from row without discounts

                // discounts over row
				$table->decimal('discount_subtotal_percentage', 10, 2);
                $table->decimal('discount_total_percentage', 10, 2);
                $table->decimal('discount_subtotal_percentage_amount', 12, 4);
                $table->decimal('discount_total_percentage_amount', 12, 4);
                $table->decimal('discount_subtotal_fixed_amount', 12, 4);
                $table->decimal('discount_total_fixed_amount', 12, 4);

				$table->decimal('discount_amount', 12, 4);                              // total amount to discount, fixed plus percentage discounts

                // subtotal with discounts
                $table->decimal('subtotal_with_discounts', 12, 4);

                // taxes
                $table->text('tax_rules');                                              // json that contain array with tax rules
                $table->decimal('tax_amount', 12, 4);                                   // tax amount over this row with discounts apply

                // total
                $table->decimal('total', 12, 4);                                        // with tax and discounts

				// fields if this row is to gift
				$table->boolean('has_gift');
				$table->string('gift_from')->nullable();
				$table->string('gift_to')->nullable();
				$table->text('gift_message')->nullable();

				$table->foreign('lang_id', 'fk01_order_row')
					->references('id')
					->on('lang')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('order_id', 'fk02_order_row')
					->references('id')
					->on('order')
					->onDelete('cascade')
					->onUpdate('cascade');
				$table->foreign('product_id', 'fk03_order_row')
					->references('id')
					->on('product')
					->onDelete('set null')
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
        Schema::dropIfExists('order_row');
	}
}