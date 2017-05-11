<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTablePaymentMethod extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('payment_method'))
		{
			Schema::create('payment_method', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->integer('id')->unsigned();
				$table->string('lang_id', 2);
				$table->string('name');
				
				// new order status
				$table->integer('order_status_successful_id')->nullable()->unsigned();
				$table->decimal('minimum_price', 12, 4)->nullable();
				$table->decimal('maximum_price', 12, 4)->nullable();
				$table->text('instructions')->nullable();
				$table->integer('sort')->unsigned()->nullable();
				$table->boolean('active');
				$table->json('data_lang')->nullable();
				
				$table->foreign('lang_id', 'fk01_payment_method')
					->references('id')
					->on('lang')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('order_status_successful_id', 'fk02_payment_method')
					->references('id')
					->on('order_status')
					->onDelete('restrict')
					->onUpdate('cascade');

				$table->primary(['id', 'lang_id'], 'pk01_payment_method');
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
		Schema::dropIfExists('payment_method');
	}
}