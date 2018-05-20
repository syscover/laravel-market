<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV8 extends Migration
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
        if(Schema::hasColumn('market_group_customer_class_tax', 'group_id'))
        {
            Schema::table('market_group_customer_class_tax', function (Blueprint $table) {
                $table->renameColumn('group_id', 'customer_group_id');
            });

            Schema::rename('market_group_customer_class_tax', 'market_customer_group_customer_class_tax');
        }

        if(Schema::hasColumn('market_cart_price_rule', 'group_ids'))
        {
            Schema::table('market_cart_price_rule', function (Blueprint $table) {
                $table->renameColumn('group_ids', 'customer_group_ids');
            });
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}