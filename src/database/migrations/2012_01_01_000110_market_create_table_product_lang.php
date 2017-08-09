<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableProductLang extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('market_product_lang'))
		{
			Schema::create('market_product_lang', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->integer('id')->unsigned();
				$table->string('lang_id', 2);
				$table->string('name');
				$table->string('slug');
				$table->text('description')->nullable();
                $table->json('data')->nullable();

                $table->timestamps();
                $table->softDeletes();
				
				$table->foreign('id', 'fk01_market_product_lang')
					->references('id')
					->on('product')
					->onDelete('cascade')
					->onUpdate('cascade');
				$table->foreign('lang_id', 'fk02_market_product_lang')
					->references('id')
					->on('lang')
					->onDelete('restrict')
					->onUpdate('cascade');
				
				$table->primary(['id', 'lang_id'], 'pk01_market_product_lang');
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
		Schema::dropIfExists('market_product_lang');
	}
}