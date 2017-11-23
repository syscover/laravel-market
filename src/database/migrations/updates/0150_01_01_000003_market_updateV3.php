<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV3 extends Migration
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
        if(! Schema::hasColumn('market_order_status', 'ix'))
        {
            Schema::table('market_order', function (Blueprint $table) {
                $table->dropForeign('fk03_market_order');
            });
            Schema::table('market_payment_method', function (Blueprint $table) {
                $table->dropForeign('fk02_market_payment_method');
            });


            Schema::table('market_order_status', function (Blueprint $table) {
                $table->dropPrimary('PRIMARY');
            });
            Schema::table('market_order_status', function (Blueprint $table) {
                $table->increments('ix')->first();
                $table->index(['id', 'lang_id'], 'ix01_market_order_status');

            });


            Schema::table('market_order', function (Blueprint $table) {
                $table->foreign('status_id', 'fk03_market_order')
                    ->references('id')
                    ->on('market_order_status')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
            });
            Schema::table('market_payment_method', function (Blueprint $table) {
                $table->foreign('order_status_successful_id', 'fk02_market_payment_method')
                    ->references('id')
                    ->on('market_order_status')
                    ->onDelete('restrict')
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