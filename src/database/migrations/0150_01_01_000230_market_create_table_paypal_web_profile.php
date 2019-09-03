<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTablePayPalWebProfile extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('market_paypal_web_profile'))
		{
			Schema::create( 'market_paypal_web_profile', function (Blueprint $table) {
				$table->engine = 'InnoDB';

                $table->string('id', 30)->primary();
				$table->string('lang_id', 2);
                $table->string('name');
                $table->boolean('is_active')->default(false);
                
                $table->timestamps();
                $table->softDeletes();
				
				$table->foreign('lang_id', 'market_paypal_web_profile_lang_id_fk')
					->references('id')
					->on('admin_lang')
					->onDelete('restrict')
					->onUpdate('cascade');
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
		Schema::dropIfExists( 'market_paypal_web_profile');
	}
}