<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableCustomerClassTax extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('market_customer_class_tax'))
        {
            Schema::create('market_customer_class_tax', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                
                $table->increments('id');
                $table->string('name');

                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('market_customer_class_tax');
    }
}