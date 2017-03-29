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
		if (! Schema::hasTable('category'))
		{
			Schema::create('category', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				
				$table->integer('id')->unsigned();
				$table->string('lang_id', 2);
				$table->integer('parent_id')->unsigned()->nullable();

				$table->string('name');
				$table->string('slug');
				$table->boolean('active');
				$table->text('description')->nullable();

				$table->string('data_lang', 255)->nullable();
				$table->text('data')->nullable();
				
				$table->foreign('lang_id', 'fk01_category')
					->references('id')
					->on('lang')
					->onDelete('restrict')
					->onUpdate('cascade');

				$table->primary(['id', 'lang_id'], 'pk01_category');
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
		if (Schema::hasTable('category'))
		{
			Schema::drop('category');
		}
	}
}