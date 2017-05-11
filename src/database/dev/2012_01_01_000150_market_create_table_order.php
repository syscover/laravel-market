<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableOrder extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('order'))
		{
			Schema::create('order', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id')->unsigned();

				$table->integer('date')->unsigned();
				$table->string('date_text');
				$table->integer('status_id')->unsigned();
				$table->string('ip');

				$table->json('data')->nullable();

				$table->integer('payment_method_id')->unsigned();
				
				// code generate by payment platform (PayPal or Bank), field to record any payment ID transaction
				$table->string('payment_id', 150);
				$table->text('comments')->nullable();

				// amounts
                $table->decimal('discount_amount', 12, 4);                              // total amount to discount, fixed plus percentage discounts
				$table->decimal('subtotal_with_discounts', 12, 4);                      // subtotal with discounts applied
                $table->decimal('tax_amount', 12, 4);                                   // total tax amount
                $table->decimal('cart_items_total_without_discounts', 12, 4);			// total of cart items. Amount with tax, without discount and without shipping

				$table->decimal('subtotal', 12, 4);										// amount without tax and without shipping
                $table->decimal('shipping_amount', 12, 4);							    // shipping amount
                $table->decimal('total', 12, 4);										// subtotal and shipping amount with tax

                // gift
                $table->boolean('has_gift');
                $table->string('gift_from')->nullable();
                $table->string('gift_to')->nullable();
                $table->text('gift_message')->nullable();

				// customer and invoice data, if is required
				$table->integer('customer_id')->unsigned()->nullable();
                $table->integer('customer_group_id')->unsigned()->nullable();
				$table->string('customer_company')->nullable();
				$table->string('customer_tin')->nullable();
				$table->string('customer_name')->nullable();
				$table->string('customer_surname')->nullable();
				$table->string('customer_email');
				$table->string('customer_phone')->nullable();
				$table->string('customer_mobile')->nullable();

				// invoice data
				$table->boolean('has_invoice');											// check if this order has invoice
				$table->boolean('invoiced')->default(false);							// check if has been created invoice on billing program
				$table->string('invoice_country_id', 2)->nullable();
				$table->string('invoice_territorial_area_1_id', 6)->nullable();
				$table->string('invoice_territorial_area_2_id', 10)->nullable();
				$table->string('invoice_territorial_area_3_id', 10)->nullable();
				$table->string('invoice_cp')->nullable();
				$table->string('invoice_locality')->nullable();
				$table->string('invoice_address')->nullable();
				$table->string('invoice_latitude')->nullable();
				$table->string('invoice_longitude')->nullable();

				// shipping data
				$table->boolean('has_shipping');
				$table->string('shipping_company')->nullable();
				$table->string('shipping_name')->nullable();
				$table->string('shipping_surname')->nullable();
				$table->string('shipping_email')->nullable();
				$table->string('shipping_phone')->nullable();
				$table->string('shipping_mobile')->nullable();
				$table->string('shipping_country_id', 2)->nullable();
				$table->string('shipping_territorial_area_1_id', 6)->nullable();
				$table->string('shipping_territorial_area_2_id', 10)->nullable();
				$table->string('shipping_territorial_area_3_id', 10)->nullable();
				$table->string('shipping_cp')->nullable();
				$table->string('shipping_locality')->nullable();
				$table->string('shipping_address')->nullable();
                $table->text('shipping_comments')->nullable();
				$table->string('shipping_latitude')->nullable();
				$table->string('shipping_longitude')->nullable();
				
				// order relations
				$table->foreign('customer_id', 'fk01_order')
					->references('id')
					->on('customer')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('status_id', 'fk02_order')
					->references('id')
					->on('order_status')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('payment_method_id', 'fk03_order')
					->references('id')
					->on('payment_method')
					->onDelete('restrict')
					->onUpdate('cascade');
                $table->foreign('customer_group_id', 'fk12_order')
                    ->references('id')
                    ->on('group')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');

				// invoice relations
				$table->foreign('invoice_country_id', 'fk04_order')
					->references('id')
					->on('country')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('invoice_territorial_area_1_id', 'fk05_order')
					->references('id')
					->on('territorial_area_1')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('invoice_territorial_area_2_id', 'fk06_order')
					->references('id')
					->on('territorial_area_2')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('invoice_territorial_area_3_id', 'fk07_order')
					->references('id')
					->on('territorial_area_3')
					->onDelete('restrict')
					->onUpdate('cascade');

				// shipping relations
				$table->foreign('shipping_country_id', 'fk08_order')
					->references('id')
					->on('country')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('shipping_territorial_area_1_id', 'fk09_order')
					->references('id')
					->on('territorial_area_1')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('shipping_territorial_area_2_id', 'fk10_order')
					->references('id')
					->on('territorial_area_2')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('shipping_territorial_area_3_id', 'fk11_order')
					->references('id')
					->on('territorial_area_3')
					->onDelete('restrict')
					->onUpdate('cascade');

				$table->index('payment_id', 'ix01_order');
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
		Schema::dropIfExists('order');
	}
}