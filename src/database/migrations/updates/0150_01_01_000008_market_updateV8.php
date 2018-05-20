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
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}