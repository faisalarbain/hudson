<?php namespace TM\Hudson;

use Guzzle\Http\Client;
use Illuminate\Support\ServiceProvider;
use Config;

class HudsonServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot(){
		$this->package('tm/hudson');
	}
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$base_url = Config::get('TM.Hudson.base_url');

		$this->app->bindShared(Report::class, function() use ($base_url){
			return new Report(
				new Browser(
					new Client($base_url)
				)
			);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
