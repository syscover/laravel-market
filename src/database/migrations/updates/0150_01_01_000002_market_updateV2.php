<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV2 extends Migration
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
        if(! Schema::hasColumn('market_category', 'ix'))
        {
            Schema::table('market_category', function (Blueprint $table) {
                $table->dropPrimary('PRIMARY');
            });

            Schema::table('market_category', function (Blueprint $table) {
                $table->increments('ix')->first();
                $table->index(['id', 'lang_id'], 'ix01_market_product_lang');
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