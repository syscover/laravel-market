<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV11 extends Migration
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
        if(! Schema::hasColumn('market_product', 'object_type'))
        {
            Schema::table('market_product', function (Blueprint $table) {
                $table->string('object_type')->nullable()->after('field_group_id');
                $table->integer('object_id')->unsigned()->nullable()->after('object_type');

                $table->index(['object_type', 'object_id'], 'ix02_market_product');
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