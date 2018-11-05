<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketUpdateV12 extends Migration
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
        if(! Schema::hasColumn('market_product', 'cost'))
        {
            Schema::table('market_product', function (Blueprint $table) {

                $table->decimal('cost', 12, 4)->nullable()->after('price_type_id');

                $table->timestamp('enable_from')->nullable()->after('subtotal');
                $table->timestamp('enable_to')->nullable()->after('enable_from');

                $table->timestamp('starts_at')->nullable()->after('enable_to');
                $table->timestamp('ends_at')->nullable()->after('starts_at');

                $table->integer('limited_capacity')->nullable()->after('ends_at');
                $table->decimal('fixed_cost', 12, 4)->nullable()->after('data');
                $table->decimal('cost_per_sale', 12, 4)->nullable()->after('cost');
            });

            Schema::table('market_order_row', function (Blueprint $table) {
                $table->decimal('cost', 12, 4)->nullable()->after('data');
                $table->decimal('total_cost', 12, 4)->nullable()->after('cost');
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