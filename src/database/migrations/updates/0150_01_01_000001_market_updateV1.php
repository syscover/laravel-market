<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV1 extends Migration
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
        if(! Schema::hasColumn('market_product_lang', 'ix'))
        {
            Schema::table('market_product_lang', function (Blueprint $table) {
                $table->dropForeign('fk01_market_product_lang');
            });

            Schema::table('market_product_lang', function (Blueprint $table) {
                $table->dropPrimary('PRIMARY');

            });
            Schema::table('market_product_lang', function (Blueprint $table) {
                $table->increments('ix')->first();
                $table->index(['id', 'lang_id'], 'ix01_market_product_lang');
            });

            Schema::table('market_product_lang', function (Blueprint $table) {
                $table->foreign('id', 'fk01_market_product_lang')
                    ->references('id')
                    ->on('market_product')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
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