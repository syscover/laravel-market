<?php namespace Syscover\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        // register routes
        if (!$this->app->routesAreCached())
            require __DIR__ . '/../../routes/api.php';

        // register migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations/' 			=> base_path('/database/migrations'),
        ], 'migrations');

        // register seeds
        $this->publishes([
            __DIR__ . '/../../database/seeds/' 					=> base_path('/database/seeds')
        ], 'seeds');

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