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
		if (! Schema::hasTable('market_payment_method'))
		{
			Schema::create('market_payment_method', function (Blueprint $table) {
				$table->engine = 'InnoDB';

                $table->increments('ix');
                $table->integer('id')->unsigned();
				$table->string('lang_id', 2);
				$table->string('name');
				
				// new order status
				$table->integer('order_status_successful_id')->nullable()->unsigned();
				$table->decimal('minimum_price', 12, 4)->nullable();
				$table->decimal('maximum_price', 12, 4)->nullable();
				$table->text('instructions')->nullable();
				$table->integer('sort')->unsigned()->nullable();
				$table->boolean('active')->default(false);
				$table->json('data_lang')->nullable();

                $table->timestamps();
                $table->softDeletes();
				
				$table->foreign('lang_id', 'fk01_market_payment_method')
					->references('id')
					->on('admin_lang')
					->onDelete('restrict')
					->onUpdate('cascade');
				$table->foreign('order_status_successful_id', 'fk02_market_payment_method')
					->references('id')
					->on('market_order_status')
					->onDelete('restrict')
					->onUpdate('cascade');

                $table->index(['id', 'lang_id'], 'ix01_market_payment_method');
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
		Schema::dropIfExists('market_payment_method');
	}
}