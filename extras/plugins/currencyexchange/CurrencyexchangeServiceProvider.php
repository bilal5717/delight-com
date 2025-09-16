<?php

namespace extras\plugins\currencyexchange;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Route;

class CurrencyexchangeServiceProvider extends ServiceProvider
{
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('currencyexchange', function ($app) {
			return new Currencyexchange($app);
		});
		
		// Register its dependencies
		$this->app->register(\Swap\Laravel\SwapServiceProvider::class);
		
		// Register their aliases
		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		$loader->alias('Swap', \Swap\Laravel\Facades\Swap::class);
	}
	
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Merge plugin config
        $this->mergeConfigFrom(realpath(__DIR__ . '/config.php'), 'swap');
        
        // Load plugin views
        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'currencyexchange');
        
        // Load plugin languages files
        $this->loadTranslationsFrom(realpath(__DIR__ . '/resources/lang'), 'currencyexchange');
        
        $this->registerCurrencyExchangeMiddleware($this->app->router);
        
        // Update the config vars
        $this->setConfigVars();
    }
    
    public function registerCurrencyExchangeMiddleware(Router $router)
    {
        // in Laravel 5.4+
        if (method_exists($router, 'aliasMiddleware')) {
            Route::aliasMiddleware('currencies', \extras\plugins\currencyexchange\app\Http\Middleware\Currencies::class);
            Route::aliasMiddleware('currencyExchange', \extras\plugins\currencyexchange\app\Http\Middleware\CurrencyExchange::class);
        } // in Laravel 5.3 and below
        else {
            Route::middleware('currencies', \extras\plugins\currencyexchange\app\Http\Middleware\Currencies::class);
            Route::middleware('currencyExchange', \extras\plugins\currencyexchange\app\Http\Middleware\CurrencyExchange::class);
        }
    }
    
    /**
     * Update the config vars
     */
    private function setConfigVars()
    {
        // Currency Exchange
		config()->set('swap.cache', 'file');
        config()->set('swap.options.cache_ttl', env('SWAP_CACHE_TTL', config('settings.currencyexchange.cache_ttl', 86400)));
		config()->set('swap.options.cache_key_prefix', 'currencies-special-');
        
        if (!empty(env('FIXER_ACCESS_KEY', config('settings.currencyexchange.fixer_access_key')))) {
            config()->set('swap.services.fixer.access_key', env('FIXER_ACCESS_KEY', config('settings.currencyexchange.fixer_access_key')));
        }
        
        if (!empty(env('CURRENCY_LAYER_ACCESS_KEY', config('settings.currencyexchange.currency_layer_access_key')))) {
            config()->set('swap.services.currency_layer.access_key', env('CURRENCY_LAYER_ACCESS_KEY', config('settings.currencyexchange.currency_layer_access_key')));
            config()->set('swap.services.currency_layer.enterprise', env('CURRENCY_LAYER_ENTERPRISE', config('settings.currencyexchange.currency_layer_enterprise')));
        }
        
        if (!empty(env('OPEN_EXCHANGE_RATES_APP_ID', config('settings.currencyexchange.open_exchange_rates_app_id')))) {
            config()->set('swap.services.open_exchange_rates.app_id', env('OPEN_EXCHANGE_RATES_APP_ID', config('settings.currencyexchange.open_exchange_rates_app_id')));
            config()->set('swap.services.open_exchange_rates.enterprise', env('OPEN_EXCHANGE_RATES_ENTERPRISE', config('settings.currencyexchange.open_exchange_rates_enterprise')));
        }
        
        if (!empty(env('CURRENCY_DATA_FEED_API_KEY', config('settings.currencyexchange.currency_data_feed_api_key')))) {
            config()->set('swap.services.currency_data_feed.api_key', env('CURRENCY_DATA_FEED_API_KEY', config('settings.currencyexchange.currency_data_feed_api_key')));
        }
        
        if (!empty(env('FORGE_API_KEY', config('settings.currencyexchange.forge_api_key')))) {
            config()->set('swap.services.forge.api_key', env('FORGE_API_KEY', config('settings.currencyexchange.forge_api_key')));
        }
        
        if (!empty(env('XIGNITE_TOKEN', config('settings.currencyexchange.xignite_token')))) {
            config()->set('swap.services.xignite.token', env('XIGNITE_TOKEN', config('settings.currencyexchange.xignite_token')));
        }
        
        if (env('CENTRAL_BANK_EU', config('settings.currencyexchange.european_central_bank'))) {
            config()->set('swap.services.european_central_bank', env('CENTRAL_BANK_EU', config('settings.currencyexchange.european_central_bank')));
        }
        
        if (env('CENTRAL_BANK_RO', config('settings.currencyexchange.national_bank_of_romania'))) {
            config()->set('swap.services.national_bank_of_romania', env('CENTRAL_BANK_RO', config('settings.currencyexchange.national_bank_of_romania')));
        }
        
        if (env('CENTRAL_BANK_TK', config('settings.currencyexchange.central_bank_of_republic_turkey'))) {
            config()->set('swap.services.central_bank_of_republic_turkey', env('CENTRAL_BANK_TK', config('settings.currencyexchange.central_bank_of_republic_turkey')));
        }
        
        if (env('CENTRAL_BANK_CZ', config('settings.currencyexchange.central_bank_of_czech_republic'))) {
            config()->set('swap.services.central_bank_of_czech_republic', env('CENTRAL_BANK_CZ', config('settings.currencyexchange.central_bank_of_czech_republic')));
        }
        
        if (env('CENTRAL_BANK_RU', config('settings.currencyexchange.russian_central_bank'))) {
            config()->set('swap.services.russian_central_bank', env('CENTRAL_BANK_RU', config('settings.currencyexchange.russian_central_bank')));
        }
        
        if (env('WEBSERVICEX', config('settings.currencyexchange.webservicex'))) {
            config()->set('swap.services.webservicex', env('WEBSERVICEX', config('settings.currencyexchange.webservicex')));
        }
        
        if (env('GOOGLE_EXCHANGE', config('settings.currencyexchange.google'))) {
            config()->set('swap.services.google', env('GOOGLE_EXCHANGE', config('settings.currencyexchange.google')));
        }
        
        if (env('CRYPTONATOR', config('settings.currencyexchange.cryptonator'))) {
            config()->set('swap.services.cryptonator', env('CRYPTONATOR', config('settings.currencyexchange.cryptonator')));
        }
    }
}
