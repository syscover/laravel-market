<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableCategory extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('market_category'))
		{
			Schema::create('market_category', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				
				$table->integer('id')->unsigned();
				$table->string('lang_id', 2);
				$table->integer('parent_id')->unsigned()->nullable();
				$table->string('name');
				$table->string('slug');
				$table->boolean('active');
				$table->text('description')->nullable();
				$table->json('data_lang', 255)->nullable();
				$table->json('data')->nullable();

                $table->timestamps();
                $table->softDeletes();
				
				$table->foreign('lang_id', 'fk01_market_category')
					->references('id')
					->on('admin_lang')
					->onDelete('restrict')
					->onUpdate('cascade');

				$table->primary(['id', 'lang_id'], 'pk01_market_category');
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
	    Schema::dropIfExists('market_category');
	}
}