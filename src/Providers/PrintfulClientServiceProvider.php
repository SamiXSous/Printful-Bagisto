<?php

namespace SamiXSous\Printful\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

use Printful\PrintfulApiClient;
use SamiXSous\Printful\Models\PrintfulKey;

class PrintfulClientServiceProvider extends ServiceProvider
{
    /**
     * Indicates id loading of the provider is deferred.
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'printful');


        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('printful::layouts.style');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('printful', function($app) {
            $printfulKey = PrintfulKey::get()->first()['api_key'];
            if($printfulKey){
                return new PrintfulApiClient($printfulKey);
            }
//            return dd($app);

        });

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );
    }

    /**
     * Get the service provided by the provider.
     * @return string
     */
    public function provides()
    {
        return 'printful';
    }
}
