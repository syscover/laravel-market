<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableOrderStatus extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('market_order_status'))
		{
			Schema::create('market_order_status', function (Blueprint $table) {
				$table->engine = 'InnoDB';

                $table->increments('ix');
				$table->integer('id')->unsigned();
				$table->string('lang_id', 2);
				$table->string('name');
				$table->boolean('active');
				$table->json('data_lang')->nullable();

                $table->timestamps();
                $table->softDeletes();
				
				$table->foreign('lang_id', 'fk01_market_order_status')
					->references('id')
					->on('admin_lang')
					->onDelete('restrict')
					->onUpdate('cascade');

                $table->index(['id', 'lang_id'], 'ix01_market_order_status');
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
		Schema::dropIfExists('market_order_status');
	}
}