<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableProductsSections extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(! Schema::hasTable('market_products_sections'))
        {
            Schema::create('market_products_sections', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->integer('product_id')->unsigned();
                $table->string('section_id', 30);

                $table->primary(['product_id', 'section_id']);
            });
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('market_products_sections');
	}
}