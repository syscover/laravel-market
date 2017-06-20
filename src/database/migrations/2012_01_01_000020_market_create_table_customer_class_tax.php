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
        if (! Schema::hasTable('customer_class_tax'))
        {
            Schema::create('customer_class_tax', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                
                $table->increments('id')->unsigned();
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
        Schema::dropIfExists('customer_class_tax');
    }
}