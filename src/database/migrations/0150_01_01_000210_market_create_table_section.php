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
                $table->string('lang_id', 2);
                $table->string('name');
                $table->string('slug');
                $table->json('data_lang', 255)->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('lang_id', 'fk01_market_section')
                    ->references('id')
                    ->on('admin_lang')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');

                $table->unique(['id', 'lang_id'], 'ui01_market_section');
                $table->index('slug', 'ix01_market_section');
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