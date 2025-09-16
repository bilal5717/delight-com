<?php

namespace extras\plugins\reviews;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Larapen\Admin\RoutesCrud;

class ReviewsServiceProvider extends ServiceProvider
{
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('reviews', function ($app) {
			return new Reviews($app);
		});
	}
	
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Load plugin views
		$this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'reviews');
		
		// Load plugin languages files
		$this->loadTranslationsFrom(realpath(__DIR__ . '/resources/lang'), 'reviews');
		
		$this->registerAdminMiddleware($this->app->router);
		$this->setupRoutes($this->app->router);
	}
	
	/**
	 * Define the routes for the application.
	 *
	 * @param Router $router
	 */
	public function setupRoutes(Router $router)
	{
		// Front
		$router->group([
			'middleware' => ['web'],
			'namespace'  => 'extras\plugins\reviews\app\Http\Controllers',
		], function ($router) {
			$router->pattern('id', '[0-9]+');
			Route::post('posts/{id}/review/create', 'ReviewController@create');
			Route::get('review/delete/{id}', 'ReviewController@delete');
		});
		
		// Admin
		$router->group(['namespace' => 'extras\plugins\reviews\app\Http\Controllers\Admin'], function ($router) {
			Route::group([
				'middleware' => ['admin', 'banned.user'],
				'prefix'     => config('larapen.admin.route_prefix', 'admin'),
			], function () {
				RoutesCrud::resource('reviews', 'ReviewController');
			});
		});
	}
	
	public function registerAdminMiddleware(Router $router)
	{
		Route::aliasMiddleware('admin', \App\Http\Middleware\Admin::class);
		Route::aliasMiddleware('banned.user', \App\Http\Middleware\BannedUser::class);
	}
}
