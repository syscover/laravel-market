<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV5 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(Schema::hasColumn('market_warehouse', 'cp'))
        {
            Schema::table('market_warehouse', function (Blueprint $table) {
                $table->renameColumn('cp', 'zip');
            });
        }

        if(Schema::hasColumn('market_tax_rate_zone', 'cp'))
        {
            Schema::table('market_tax_rate_zone', function (Blueprint $table) {
                $table->renameColumn('cp', 'zip');
            });
        }

        if(Schema::hasColumn('market_order', 'invoice_cp'))
        {
            Schema::table('market_order', function (Blueprint $table) {
                $table->renameColumn('invoice_cp', 'invoice_zip');
            });
        }

        if(Schema::hasColumn('market_order', 'shipping_cp'))
        {
            Schema::table('market_order', function (Blueprint $table) {
                $table->renameColumn('shipping_cp', 'shipping_zip');
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