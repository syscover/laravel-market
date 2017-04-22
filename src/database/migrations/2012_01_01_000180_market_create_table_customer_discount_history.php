<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableCustomerDiscountHistory extends Migration
{
	/**
	 * Table to registry each discount used for customer
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('customer_discount_history'))
		{
			Schema::create('customer_discount_history', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id')->unsigned();

				$table->integer('date')->unsigned();            // registry date
				$table->integer('customer_id')->unsigned();
				$table->integer('order_id')->unsigned();

                // if order is canceled, you can deactivate discounts
                $table->boolean('active')->default(true);

                // see config/market.php section Discounts rules families
				// 1 - discount from, cart price rule
				// 2 - discount from, catalog price rule
				// 3 - discount from, customer rule discount
				$table->tinyInteger('rule_family_id')->unsigned();

				// discount ID should come from customer discount
				//$table->integer('customer_discount_id')->unsigned()->nullable();

                // id of the rule applicable discount
				$table->integer('rule_id')->unsigned();
				
				$table->boolean('has_coupon')->default(false);
				$table->string('coupon_code')->nullable();

				// reference to table 001_017_text
				$table->integer('name_text_id')->unsigned();
				$table->integer('description_text_id')->nullable()->unsigned();

				// 001_017_text table values in the language that has exchanged the discount,
				// get the value in the user's language to have a reference in case of delete from the table 001_017_text
				$table->string('name_text_value');
				$table->text('description_text_value')->nullable();

                // see config/market.php section Discount type on shopping cart
                // 1 - without discount
                // 2 - discount percentage subtotal
                // 3 - discount fixed amount subtotal
                // 4 - discount percentage total
                // 5 - discount fixed amount total
				$table->tinyInteger('discount_type_id')->unsigned()->nullable();

                // fixed amount to discount over shopping cart
				$table->decimal('discount_fixed_amount', 12, 4)->nullable();

                // percentage to discount over shopping cart
				$table->decimal('discount_percentage', 12, 4)->nullable();

                // limit amount to discount, if the discount is a percentage
				$table->decimal('maximum_discount_amount', 12, 4)->nullable();

                // total discount amount apply with this rule
				$table->decimal('discount_amount', 12, 4)->nullable();

				// check if apply discount to shipping amount
				$table->boolean('apply_shipping_amount');
				
				// check if this discount has free shipping
				$table->boolean('free_shipping');

				// price rules apply over shopping cart, this column has rule in json format
				$table->text('rules')->nullable();
				
				$table->foreign('customer_id', 'fk01_customer_discount_history')
					->references('id')
					->on('customer')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('order_id', 'fk02_customer_discount_history')
					->references('id')
					->on('order')
					->onDelete('restrict')
					->onUpdate('cascade');

				$table->index('rule_id', 'ix01_customer_discount_history');
				$table->index('coupon_code', 'ix02_customer_discount_history');
				$table->index('active', 'ix03_customer_discount_history');
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
		Schema::dropIfExists('customer_discount_history');
	}
}