<?php

namespace IlBronza\Operators;

use IlBronza\Operators\Models\ClientOperator;
use IlBronza\Operators\Models\Contracttype;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\OperatorContracttype;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

use function array_replace_recursive;

class OperatorsServiceProvider extends ServiceProvider
{
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot() : void
	{
		Relation::morphMap([
			'Operator' => Operator::getProjectClassName(),
			'OperatorContracttype' => OperatorContracttype::getProjectClassName(),
			'ClientOperator' => ClientOperator::getProjectClassName(),
			'Contracttype' => Contracttype::getProjectClassName(),
		]);

		$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'operators');
		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'operators');
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
		$this->loadRoutesFrom(__DIR__ . '/routes.php');

		// Publishing is only necessary when using the CLI.
		if ($this->app->runningInConsole())
		{
			$this->bootForConsole();
		}
	}

	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register() : void
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/operators.php', 'operators');

		$this->app->make('IlBronza\Operators\Http\Controllers\CrudOperatorsController');

		// Register the service the package provides.
		$this->app->singleton('operators', function ($app)
		{
			return new Operators;
		});
	}

	protected function mergeConfigFrom($path, $key)
	{
		if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
			$config = $this->app->make('config');

			$config->set($key, array_replace_recursive(
				require $path, $config->get($key, [])
			));
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['operators'];
	}

	/**
	 * Console-specific booting.
	 *
	 * @return void
	 */
	protected function bootForConsole() : void
	{
		// Publishing the configuration file.
		$this->publishes([
			__DIR__ . '/../config/operators.php' => config_path('operators.php'),
		], 'operators.config');

		// Publishing the views.
		/*$this->publishes([
			__DIR__.'/../resources/views' => base_path('resources/views/vendor/ilbronza'),
		], 'operators.views');*/

		// Publishing assets.
		/*$this->publishes([
			__DIR__.'/../resources/assets' => public_path('vendor/ilbronza'),
		], 'operators.views');*/

		// Publishing the translation files.
		/*$this->publishes([
			__DIR__.'/../resources/lang' => resource_path('lang/vendor/ilbronza'),
		], 'operators.views');*/

		// Registering package commands.
		// $this->commands([]);
	}
}
