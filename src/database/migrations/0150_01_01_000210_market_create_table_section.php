<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketCreateTableSection extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('market_section'))
        {
            Schema::create('market_section', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('ix');
                $table->string('id', 30);
                $table->string('name');

                $table->timestamps();
                $table->softDeletes();

                $table->index('id', 'ix01_market_section');
                $table->unique('id', 'ui01_market_section');
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
        Schema::dropIfExists('market_section');
    }
}