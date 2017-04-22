<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableCartPriceRule extends Migration
{
	/**
	 * Tabla que establece las reglas de precios que se aplican al carro de compra
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasTable('cart_price_rule'))
		{
			Schema::create('cart_price_rule', function (Blueprint $table) {
				$table->engine = 'InnoDB';

				$table->increments('id')->unsigned();

				// referencia a la tabla text
				$table->integer('name_text_id')->unsigned();
				$table->integer('description_text_id')->unsigned()->nullable();
				$table->boolean('active');
				
				// define si este regla se puede combiar con otras
				$table->boolean('combinable');

				$table->boolean('has_coupon');
				$table->string('coupon_code')->nullable();
				
				// veces que el cupon se puede usar por usuario
				$table->integer('uses_customer')->unsigned()->nullable();
				
				// veces que el cupon se puede usar
				$table->integer('uses_coupon')->unsigned()->nullable();
				
				// total de veces que el descuento ha sido usado
				$table->integer('total_used')->unsigned();

				// fechas de validez
				$table->integer('enable_from')->unsigned()->nullable();
				$table->string('enable_from_text')->nullable();
				$table->integer('enable_to')->unsigned()->nullable();
				$table->string('enable_to_text')->nullable();

                // see config/market.php section Discount type on shopping cart
                // 1 - without discount
                // 2 - discount percentage subtotal
                // 3 - discount fixed amount subtotal
                // 4 - discount percentage total
                // 5 - discount fixed amount total
				$table->tinyInteger('discount_type_id')->unsigned()->nullable();

                // fixed amount to discount over shopping cart
				$table->decimal('discount_fixed_amount', 12, 4)->nullable();

				// Porcentaje de descuento sobre una cantidad
				$table->decimal('discount_percentage', 12, 4)->nullable();

                // máxima cantidad a descontar
				$table->decimal('maximum_discount_amount', 12, 4)->nullable();

                // se aplica el descuento al precio de transporte
				$table->boolean('apply_shipping_amount');

                // este descuento dispone de transporte gratuito
				$table->boolean('free_shipping');

				// reglas, campo para una futura implementación de reglas
				$table->text('rules')->nullable();

				// campo que contiene json con la información de idiomas creados
				$table->string('data_lang')->nullable();

				// índice para mejorar las búsquedas de los códigos de cupón
				$table->index('coupon_code', 'ix01_cart_price_rule');
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
        Schema::dropIfExists('cart_price_rule');
	}
}