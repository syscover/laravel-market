<?php namespace Syscover\Market;

use Illuminate\Support\ServiceProvider;

class MarketServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        // register routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        // register migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // register translations
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'market');

        // register seeds
        $this->publishes([
            __DIR__ . '/../../database/seeds/' => base_path('/database/seeds')
        ], 'seeds');

        // register config files
        $this->publishes([
            __DIR__ . '/../../config/pulsar.market.php' => config_path('pulsar.market.php'),
        ]);

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        //
	}
}