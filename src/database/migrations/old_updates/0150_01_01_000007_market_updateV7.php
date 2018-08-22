<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV7 extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');
    }

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('market_cart_price_rule', function (Blueprint $table) {
            $table->boolean('active')->default(false)->change();
            $table->boolean('combinable')->default(false)->change();
            $table->boolean('has_coupon')->default(false)->change();
            $table->boolean('apply_shipping_amount')->default(false)->change();
            $table->boolean('free_shipping')->default(false)->change();
        });

        Schema::table('market_payment_method', function (Blueprint $table) {
            $table->boolean('active')->default(false)->change();
        });

        Schema::table('market_warehouse', function (Blueprint $table) {
            $table->boolean('active')->default(false)->change();
        });

        Schema::table('market_order_status', function (Blueprint $table) {
            $table->boolean('active')->default(false)->change();
        });

        Schema::table('market_category', function (Blueprint $table) {
            $table->boolean('active')->default(false)->change();
        });

        Schema::table('market_customer_discount_history', function (Blueprint $table) {
            $table->boolean('apply_shipping_amount')->default(false)->change();
            $table->boolean('free_shipping')->default(false)->change();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}