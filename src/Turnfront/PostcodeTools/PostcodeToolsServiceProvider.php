<?php namespace Turnfront\PostcodeTools;

use Illuminate\Support\ServiceProvider;

class PostcodeToolsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

  public function boot(){
    \Validator::extend("postcode", function ($attribute, $value, $parameters){
      return \Turnfront\PostcodeTools\Facades\PostcodeTools::checkPostcode($value);
    });
  }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
    \App::bind("Turnfront\\PostcodeTools\\Contracts\\PostcodeToolsInterface", function ($app){
      return new Services\PostcodeTools();
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