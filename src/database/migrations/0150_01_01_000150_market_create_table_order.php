<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class MarketCreateTableOrder extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('market_order'))
		{
			Schema::create('market_order', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id');
				$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
				$table->integer('payment_method_id')->unsigned();
				$table->integer('status_id')->unsigned();
				$table->string('ip')->nullable();
				$table->json('data')->nullable();
				$table->text('comments')->nullable();

                $table->string('transaction_id')->nullable();                                               // code generate by payment platform (PayPal or Bank), field to record any payment ID transaction
                $table->string('tracking_id')->nullable();                                                  // code generate by shipping company to get tracking of shipping

                //****************
                //* amounts
                //****************
                $table->decimal('discount_amount', 12, 4)->default(0);                         // total amount to discount, fixed plus percentage discounts
				$table->decimal('subtotal_with_discounts', 12, 4)->default(0);                 // subtotal with discounts applied
                $table->decimal('tax_amount', 12, 4)->default(0);                              // total tax amount
                $table->decimal('cart_items_total_without_discounts', 12, 4)->default(0);      // total of cart items. Amount with tax, without discount and without shipping
				$table->decimal('subtotal', 12, 4)->default(0);					                // amount without tax and without shipping
                $table->decimal('shipping_amount', 12, 4)->default(0);			                // shipping amount
                $table->decimal('total', 12, 4)->default(0); 				                    // subtotal and shipping amount with tax

                //****************
                //* gift
                //****************
                $table->boolean('has_gift')->default(false);
                $table->string('gift_from')->nullable();
                $table->string('gift_to')->nullable();
                $table->text('gift_message')->nullable();
                $table->text('gift_comments')->nullable();

                //****************
                //* customer
                //****************
				$table->integer('customer_id')->unsigned();                                 // Customer ID
                $table->integer('customer_group_id')->unsigned();                           // Group ID
				$table->string('customer_company')->nullable();
				$table->string('customer_tin')->nullable();
				$table->string('customer_name')->nullable();
				$table->string('customer_surname')->nullable();
				$table->string('customer_email');
				$table->string('customer_mobile')->nullable();
				$table->string('customer_phone')->nullable();

				//****************
                //* invoice data
                //****************
				$table->boolean('has_invoice')->default(false);	    					    // Check if this order has invoice
				$table->boolean('invoiced')->default(false);							        // Check if has been created invoice on billing program
                $table->string('invoice_number')->nullable();                               // If has invoice, set invoice number
                $table->string('invoice_company')->nullable();
                $table->string('invoice_tin')->nullable();
                $table->string('invoice_name')->nullable();
                $table->string('invoice_surname')->nullable();
                $table->string('invoice_email')->nullable();
                $table->string('invoice_mobile')->nullable();
                $table->string('invoice_phone')->nullable();
                $table->string('invoice_country_id', 2)->nullable();
				$table->string('invoice_territorial_area_1_id', 6)->nullable();
				$table->string('invoice_territorial_area_2_id', 10)->nullable();
				$table->string('invoice_territorial_area_3_id', 10)->nullable();
				$table->string('invoice_zip')->nullable();
				$table->string('invoice_locality')->nullable();
				$table->string('invoice_address')->nullable();
				$table->string('invoice_latitude')->nullable();
				$table->string('invoice_longitude')->nullable();
                $table->text('invoice_comments')->nullable();

                //****************
                //* shipping data
                //****************
				$table->boolean('has_shipping')->default(false);
				$table->string('shipping_company')->nullable();
				$table->string('shipping_name')->nullable();
				$table->string('shipping_surname')->nullable();
				$table->string('shipping_email')->nullable();
				$table->string('shipping_mobile')->nullable();
				$table->string('shipping_phone')->nullable();
				$table->string('shipping_country_id', 2)->nullable();
				$table->string('shipping_territorial_area_1_id', 6)->nullable();
				$table->string('shipping_territorial_area_2_id', 10)->nullable();
				$table->string('shipping_territorial_area_3_id', 10)->nullable();
				$table->string('shipping_zip')->nullable();
				$table->string('shipping_locality')->nullable();
				$table->string('shipping_address')->nullable();
				$table->string('shipping_latitude')->nullable();
				$table->string('shipping_longitude')->nullable();
                $table->text('shipping_comments')->nullable();

                $table->timestamps();
                $table->softDeletes();
				
				// order relations
				$table->foreign('customer_id', 'fk01_market_order')
					->references('id')
					->on('crm_customer')
					->onDelete('restrict')
					->onUpdate('cascade');
                $table->foreign('customer_group_id', 'fk02_market_order')
                    ->references('id')
                    ->on('crm_group')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
				$table->foreign('status_id', 'fk03_market_order')
					->references('id')
					->on('market_order_status')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('payment_method_id', 'fk04_market_order')
					->references('id')
					->on('market_payment_method')
					->onDelete('restrict')
					->onUpdate('cascade');


				// invoice relations
				$table->foreign('invoice_country_id', 'fk05_market_order')
					->references('id')
					->on('admin_country')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('invoice_territorial_area_1_id', 'fk06_market_order')
					->references('id')
					->on('admin_territorial_area_1')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('invoice_territorial_area_2_id', 'fk07_market_order')
					->references('id')
					->on('admin_territorial_area_2')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('invoice_territorial_area_3_id', 'fk08_market_order')
					->references('id')
					->on('admin_territorial_area_3')
					->onDelete('restrict')
					->onUpdate('cascade');

				// shipping relations
				$table->foreign('shipping_country_id', 'fk09_market_order')
					->references('id')
					->on('admin_country')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('shipping_territorial_area_1_id', 'fk10_market_order')
					->references('id')
					->on('admin_territorial_area_1')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('shipping_territorial_area_2_id', 'fk11_market_order')
					->references('id')
					->on('admin_territorial_area_2')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('shipping_territorial_area_3_id', 'fk12_market_order')
					->references('id')
					->on('admin_territorial_area_3')
					->onDelete('restrict')
					->onUpdate('cascade');

				$table->index('transaction_id', 'ix01_market_order');
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
		Schema::dropIfExists('market_order');
	}
}