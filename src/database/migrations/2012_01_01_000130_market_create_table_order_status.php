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
		if (! Schema::hasTable('order_status'))
		{
			Schema::create('order_status', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->integer('id')->unsigned();
				$table->string('lang_id', 2);
				$table->string('name');
				$table->boolean('active');
				$table->string('data_lang')->nullable();
				
				$table->foreign('lang_id', 'fk01_order_status')
					->references('id')
					->on('lang')
					->onDelete('restrict')
					->onUpdate('cascade');

				$table->primary(['id', 'lang_id'], 'pk01_order_status');
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
		Schema::dropIfExists('order_status');
	}
}