<?php

namespace DevMarketer\EasyNav;

use Illuminate\Support\ServiceProvider;

class EasyNavServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      $this->publishes([
        __DIR__.'/../../config/easynav.php' => config_path('easynav.php'),
      ], 'easynav');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
			$this->mergeConfigFrom(
        __DIR__.'/../../config/easynav.php', 'easynav'
	    );
      $this->app->bind('easynav', function($app)
      {
        return $this->app->make('DevMarketer\EasyNav\EasyNav');
      });
    }
}
