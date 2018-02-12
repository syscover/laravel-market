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

                $table->json('names')->nullable(); // name value in different languages
                $table->json('descriptions')->nullable(); //description value in different languages

                $table->boolean('active');

                // groups that could apply the discount
                $table->json('group_ids')->nullable();
                // customers that could apply the discount
                $table->json('customer_ids')->nullable();

                // define if this rule can to be combined with other rule
                $table->boolean('combinable');
                // short to apply discounts
                $table->integer('priority')->unsigned()->nullable();

                $table->boolean('has_coupon');
                $table->string('coupon_code')->nullable();

                // times a coupon can be used per user
                $table->integer('customer_uses')->unsigned()->nullable();
                // times a coupon can be used
                $table->integer('coupon_uses')->unsigned()->nullable();

                // total times the discount has been used
                $table->integer('total_uses')->unsigned()->default(0);

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
                $table->tinyInteger('discount_type_id')->unsigned();

                // fixed amount to discount over shopping cart
                $table->decimal('discount_fixed_amount', 12, 4)->nullable();

                // discount percentage on an amount
                $table->decimal('discount_percentage', 12, 4)->nullable();
                // maximum amount to discount
                $table->decimal('maximum_discount_amount', 12, 4)->nullable();

                // check if the discount is applied to the transport price
                $table->boolean('apply_shipping_amount');

                // this discount has free shipping
                $table->boolean('free_shipping');

                // rules that will determinate that products will be applied this discounts
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