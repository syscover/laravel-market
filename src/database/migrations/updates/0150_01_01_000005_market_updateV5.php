<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV5 extends Migration
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
        if(! Schema::hasColumn('market_payment_method', 'object_id'))
        {
            Schema::table('market_order', function (Blueprint $table) {
                $table->dropForeign('fk04_market_order');
            });


            Schema::table('market_payment_method', function (Blueprint $table) {
                $table->dropPrimary('PRIMARY');
                $table->renameColumn('id', 'object_id');
            });

            Schema::table('market_payment_method', function (Blueprint $table) {
                $table->increments('id')->first();
                $table->index(['object_id', 'lang_id'], 'ix01_market_payment_method');

            });


            Schema::table('market_order', function (Blueprint $table) {
                $table->foreign('payment_method_id', 'fk04_market_order')
                    ->references('object_id')
                    ->on('market_payment_method')
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