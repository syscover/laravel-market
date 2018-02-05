<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableCartPriceRule extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('market_cart_price_rule'))
		{
			Schema::create('market_cart_price_rule', function (Blueprint $table) {
				$table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->json('name')->nullable(); // name value in different languages
                $table->json('description')->nullable(); //description value in different languages

                $table->boolean('active');

                // groups that could apply the discount
                $table->json('groups_id')->nullable();
                // customers that could apply the discount
                $table->json('customers_id')->nullable();

                // define if this rule can to be combined with other rule
                $table->boolean('combinable');
                // order to apply discounts
                $table->integer('priority')->unsigned()->nullable();

                $table->boolean('has_coupon');
                $table->string('coupon_code')->nullable();

                // times a coupon can be used per user
                $table->integer('used_customer')->unsigned()->nullable();
                // times a coupon can be used
                $table->integer('used_coupon')->unsigned()->nullable();

                // total times the discount has been used
                $table->integer('total_used')->unsigned();

                $table->timestamp('enable_from')->nullable();
                $table->timestamp('enable_to')->nullable();

                // check if this rules are valid to apply this discount
                $table->json('condition_rules')->nullable();

                // see config/pulsar-market.php section Discount type on shopping cart
                // 1 - without discount
                // 2 - discount percentage subtotal
                // 3 - discount fixed amount subtotal
                // 4 - discount percentage total
                // 5 - discount fixed amount total
                $table->tinyInteger('discount_type_id')->unsigned()->nullable();

                // fixed amount to discount over shopping cart
                $table->decimal('discount_fixed_amount', 12, 4)->nullable();

                // discount percentage on an amount
                $table->decimal('discount_percentage', 12, 4)->nullable();
                // maximum amount to discount
                $table->decimal('maximum_discount_amount', 12, 4)->nullable();

                // check if the discount is applied to the transport price
                $table->boolean('apply_shipping_amount');

                // this discount has free transportation
                $table->boolean('free_shipping');

                // products where will be applied this discounts
                $table->json('product_rules')->nullable();

                $table->json('data_lang')->nullable();

                $table->index('coupon_code', 'ix01_market_cart_price_rule');
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
        Schema::dropIfExists('market_cart_price_rule');
	}
}