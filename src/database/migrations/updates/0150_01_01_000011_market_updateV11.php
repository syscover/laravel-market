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
        if(! Schema::hasColumn('market_section', 'slug'))
        {
            Schema::table('market_section', function (Blueprint $table) {
                $table->dropUnique('ui01_market_section');
                $table->dropIndex('ix01_market_section');

                $table->string('lang_id')->after('id');
                $table->string('slug')->after('name');

                $table->foreign('lang_id', 'fk01_market_section')
                    ->references('id')
                    ->on('admin_lang')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');

                $table->unique(['id', 'lang_id'], 'ui01_market_section');
                $table->index('slug', 'ix01_market_section');

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