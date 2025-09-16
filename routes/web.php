<?php

use App\Http\Controllers\Account\CompanyController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Larapen\Admin\RoutesCrud as CRUD;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Upgrading
|--------------------------------------------------------------------------
|
| The upgrading process routes
|
*/

$languages = implode('|', config('sitemap-languages.supported'));

Route::group([
    'namespace' => 'App\Http\Controllers\Install',
    'middleware' => ['web', 'no.http.cache'],
], function () {
    Route::get('upgrade', 'UpdateController@index');
    Route::post('upgrade/run', 'UpdateController@run');
});


/*
|--------------------------------------------------------------------------
| Installation
|--------------------------------------------------------------------------
|
| The installation process routes
|
*/
Route::group([
    'namespace' => 'App\Http\Controllers\Install',
    'middleware' => ['web', 'install.checker', 'no.http.cache'],
    'prefix' => 'install',
], function () {
    Route::get('/', 'InstallController@starting');
    Route::get('site_info', 'InstallController@siteInfo');
    Route::post('site_info', 'InstallController@siteInfo');
    Route::get('system_compatibility', 'InstallController@systemCompatibility');
    Route::get('database', 'InstallController@database');
    Route::post('database', 'InstallController@database');
    Route::get('database_import', 'InstallController@databaseImport');
    Route::get('cron_jobs', 'InstallController@cronJobs');
    Route::get('finish', 'InstallController@finish');
});


/*
|--------------------------------------------------------------------------
| Back-end
|--------------------------------------------------------------------------
|
| The admin panel routes
|
*/
Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'middleware' => ['web', 'install.checker'],
    'prefix' => config('larapen.admin.route_prefix', 'admin'),
], function ($router) {
    // Auth
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

    // Admin Panel Area
    Route::group([
        'middleware' => ['admin', 'clearance', 'banned.user', 'no.http.cache'],
    ], function ($router) {
        // Dashboard
        Route::get('dashboard', 'DashboardController@dashboard');
        Route::get('/', 'DashboardController@redirect');

        //Indexing Status
        Route::get('indexing/status', 'IndexingController@index');
        Route::post('indexing/status', 'IndexingController@status')->name('indexing.status');

        // Extra (must be called before CRUD)
        Route::get('homepage/{action}', 'HomeSectionController@reset')->where('action', 'reset_(.*)');
        Route::get('languages/sync_files', 'LanguageController@syncFilesLines');
        Route::get('languages/texts/{lang?}/{file?}', 'LanguageController@showTexts');
        Route::post('languages/texts/{lang}/{file}', 'LanguageController@updateTexts');
        Route::get('permissions/create_default_entries', 'PermissionController@createDefaultEntries');
        Route::get('blacklists/add', 'BlacklistController@banUserByEmail');
        Route::get('categories/rebuild-nested-set-nodes', 'CategoryController@rebuildNestedSetNodes');

        // CRUD
        CRUD::resource('advertisings', 'AdvertisingController');
        CRUD::resource('blacklists', 'BlacklistController');
        CRUD::resource('categories', 'CategoryController');
        CRUD::resource('categories/{catId}/subcategories', 'CategoryController');
        CRUD::resource('categories/{catId}/custom_fields', 'CategoryFieldController');
        CRUD::resource('cities', 'CityController');
        CRUD::resource('countries', 'CountryController');
        CRUD::resource('countries/{countryCode}/cities', 'CityController');
        CRUD::resource('countries/{countryCode}/admins1', 'SubAdmin1Controller');
        CRUD::resource('currencies', 'CurrencyController');
        CRUD::resource('custom_fields', 'FieldController');
        CRUD::resource('custom_fields/{cfId}/options', 'FieldOptionController');
        CRUD::resource('custom_fields/{cfId}/categories', 'CategoryFieldController');
        CRUD::resource('genders', 'GenderController');
        CRUD::resource('homepage', 'HomeSectionController');
        CRUD::resource('admins1/{admin1Code}/cities', 'CityController');
        CRUD::resource('admins1/{admin1Code}/admins2', 'SubAdmin2Controller');
        CRUD::resource('admins2/{admin2Code}/cities', 'CityController');
        CRUD::resource('languages', 'LanguageController');
        CRUD::resource('meta_tags', 'MetaTagController');
        CRUD::resource('packages', 'PackageController');
        CRUD::resource('pages', 'PageController');
        CRUD::resource('service_settings', 'ServiceSettingController');
        CRUD::resource('payments', 'PaymentController');
        CRUD::resource('payment_methods', 'PaymentMethodController');
        CRUD::resource('permissions', 'PermissionController');
        CRUD::resource('pictures', 'PictureController');
        CRUD::resource('posts', 'PostController');
        CRUD::resource('p_types', 'PostTypeController');
        CRUD::resource('product_types', 'ProductTypeController');
        CRUD::resource('report_types', 'ReportTypeController');
        CRUD::resource('roles', 'RoleController');
        CRUD::resource('settings', 'SettingController');
        CRUD::resource('time_zones', 'TimeZoneController');
        CRUD::resource('users', 'UserController');

        Route::get('user-primary-email/{id}', 'UserController@userPrimaryEmail')->name('user-primary-email');
        Route::put('update-primary-email/{id}', 'UserController@updateEmail')->name('update-primary-email');

        //company
        Route::get('view-company/{id}', 'CompanyController@viewCompany')->name('view-company');
        Route::get('edit-company/{id}', 'CompanyController@editCompany')->name('edit-company');
        Route::post('update-company/', 'CompanyController@updateCompany')->name('update-company');
        Route::get('/view-company/{currency}/{getcurrency?}', 'CompanyController@getForm')->name('getForm');

        // Monthly Image Upload URLs Start
        CRUD::resource('monthly-image', 'SliderImageController');
        // Monthly Image Upload URLs End

        // Others
        Route::get('account', 'UserController@account');
        Route::post('ajax/{table}/{field}', 'InlineRequestController@make');

        // Backup
        Route::get('backups', 'BackupController@index');
        Route::put('backups/create', 'BackupController@create');
        Route::get('backups/download/{file_name?}', 'BackupController@download');
        Route::delete('backups/delete/{file_name?}', 'BackupController@delete')->where('file_name', '(.*)');

        // Actions
        Route::get('actions/clear_cache', 'ActionController@clearCache');
        Route::get('actions/clear_images_thumbnails', 'ActionController@clearImagesThumbnails');
        Route::get('actions/maintenance/{mode}', 'ActionController@maintenance')->where('mode', '(down|up)');

        // Re-send Email or Phone verification message
        Route::get('verify/user/{id}/resend/email', 'UserController@reSendVerificationEmail');
        Route::get('verify/user/{id}/resend/sms', 'UserController@reSendVerificationSms');
        Route::get('verify/post/{id}/resend/email', 'PostController@reSendVerificationEmail');
        Route::get('verify/post/{id}/resend/sms', 'PostController@reSendVerificationSms');

        // Plugins
        Route::get('plugins', 'PluginController@index');
        Route::post('plugins/{plugin}/install', 'PluginController@install');
        Route::get('plugins/{plugin}/install', 'PluginController@install');
        Route::get('plugins/{plugin}/uninstall', 'PluginController@uninstall');
        Route::get('plugins/{plugin}/delete', 'PluginController@delete');

        // System Info
        Route::get('system', 'SystemController@systemInfo');
    });
});


/*
|--------------------------------------------------------------------------
| Front-end
|--------------------------------------------------------------------------
|
| The not translated front-end routes
|
*/

Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => ['web', 'install.checker'],
], function ($router) use ($languages) {
    // Select Language
    Route::get('lang/{code}', 'Locale\SetLocaleController@redirect');
    Route::get('page/{slug}/lang/{code}', 'PageController@cmsWithLang');

    // Special pages with language routes
    Route::get('sitemap/lang/{code}', 'SitemapController@indexWithLang')
        ->where('code', $languages);

    Route::get('contact/lang/{code}', 'PageController@contactWithLang')
        ->where('code', $languages);

    Route::get('pricing/lang/{code}', 'PageController@pricingWithLang')
        ->where('code', $languages);

    //FIXED ROUTES:
    Route::get('create/lang/{code}', function ($code) {
        return app('App\Http\Controllers\Locale\SetLocaleController')->redirectWithPath('create', $code);
    })->where('code', $languages);

    Route::get('register/lang/{code}', function ($code) {
        return app('App\Http\Controllers\Locale\SetLocaleController')->redirectWithPath('register', $code);
    })->where('code', $languages);

    Route::get('login/lang/{code}', function ($code) {
        return app('App\Http\Controllers\Locale\SetLocaleController')->redirectWithPath('login', $code);
    })->where('code', $languages);

    Route::get('password/reset/lang/{code}', function ($code) {
        return app('App\Http\Controllers\Locale\SetLocaleController')->redirectWithPath('password/reset', $code);
    })->where('code', $languages);

    // FILES/
    Route::get('file', 'FileController@show');
    Route::get('js/fileinput/locales/{code}.js', 'FileController@fileInputLocales');

    // SEO
    Route::get('sitemaps.xml', 'SitemapsController@index');

    // Impersonate (As admin user, login as an another user)
    Route::group(['middleware' => 'auth'], function ($router) {
        Route::impersonate();
    });
});


/*
|--------------------------------------------------------------------------
| Front-end
|--------------------------------------------------------------------------
|
| The translated front-end routes
|
*/
Route::group([
    'namespace' => 'App\Http\Controllers',
], function ($router) use ($languages) {
    Route::group(['middleware' => ['web', 'install.checker']], function ($router) use ($languages) {
        // Country Code Pattern
        $countryCodePattern = implode('|', array_map('strtolower', array_keys(getCountries())));
        $countryCodePattern = !empty($countryCodePattern) ? $countryCodePattern : 'us';
        /*
         * NOTE:
         * '(?i:foo)' : Make 'foo' case-insensitive
         */
        $countryCodePattern = '(?i:' . $countryCodePattern . ')';
        $router->pattern('countryCode', $countryCodePattern);


        // HOMEPAGE
        Route::get('/', 'HomeController@index')
            ->where('code', $languages);
        Route::get(dynamicRoute('routes.countries'), 'CountriesController@index');

        Route::post('/toggle-view', 'HomeController@toggleView')->name('toggle-view');
        Route::post('/cart/reset-order-status', 'HomeController@resetOrderStatus')->name('cart.resetOrderStatus');
        Route::post('/cart/update-order-status', 'HomeController@updateOrderStatus')->name('cart.updateOrderStatus');
        Route::group(['middleware' => 'auth'], function () {
            Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout');
            Route::post('/checkout/complete', [CheckoutController::class, 'complete'])->name('checkout.complete');
            Route::get('/order/confirmation/{orderId}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
        });
        Route::match(['get', 'delete'], '/cart', 'HomeController@showCart')->name('carts');
        Route::get('/cart/count', function () {
            return response()->json(['count' => \App\Models\Cart::where('user_id', auth()->id())->count()]);
        })->name('cart.count');

        Route::delete('/cart/remove/{id}', 'HomeController@removeCart')->name('cart.remove');
        // AUTH
        Route::group(['middleware' => ['guest', 'no.http.cache']], function ($router) {
            // Registration Routes...
            Route::get(dynamicRoute('routes.register'), 'Auth\RegisterController@showRegistrationForm');
            Route::post(dynamicRoute('routes.register'), 'Auth\RegisterController@register');
            Route::get('register/finish', 'Auth\RegisterController@finish');

            // Authentication Routes...
            Route::get(dynamicRoute('routes.login'), 'Auth\LoginController@showLoginForm');
            Route::post(dynamicRoute('routes.login'), 'Auth\LoginController@login');

            // Forgot Password Routes...
            Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
            Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

            // Reset Password using Token
            Route::get('password/token', 'Auth\ForgotPasswordController@showTokenRequestForm');
            Route::post('password/token', 'Auth\ForgotPasswordController@sendResetToken');

            // Reset Password using Link (Core Routes...)
            Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
            Route::post('password/reset', 'Auth\ResetPasswordController@reset');

            // Social Authentication
            $router->pattern('provider', 'facebook|linkedin|twitter|google');
            Route::get('auth/{provider}', 'Auth\SocialController@redirectToProvider');
            Route::get('auth/{provider}/callback', 'Auth\SocialController@handleProviderCallback');
        });

        // Email Address or Phone Number verification
        $router->pattern('field', 'email|phone');
        Route::get('verify/user/{id}/resend/email', 'Auth\RegisterController@reSendVerificationEmail');
        Route::get('verify/user/{id}/resend/sms', 'Auth\RegisterController@reSendVerificationSms');
        Route::get('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');
        Route::post('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');

        // User Logout
        Route::get(dynamicRoute('routes.logout'), 'Auth\LoginController@logout');


        // POSTS
        Route::group(['namespace' => 'Post'], function ($router) {
            $router->pattern('id', '[0-9]+');
            // $router->pattern('slug', '.*');
            $bannedSlugs = collect(config('routes'))->filter(function ($value, $key) {
                return (!Str::contains($key, '.') && !empty($value));
            })->flatten()->toArray();
            if (!empty($bannedSlugs)) {
                /*
                 * NOTE:
                 * '^(?!companies|users)$' : Don't match 'companies' or 'users'
                 * '^(?=.*)$'              : Match any character
                 * '^((?!\/).)*$'          : Match any character, but don't match string with '/'
                 */
                $router->pattern('slug', '^(?!' . implode('|', $bannedSlugs) . ')(?=.*)((?!\/).)*$');
            } else {
                $router->pattern('slug', '^(?=.*)((?!\/).)*$');
            }

            // SingleStep Post creation
            Route::group(['namespace' => 'CreateOrEdit\SingleStep'], function ($router) {
                Route::get('create', 'CreateController@getForm');
                Route::post('create', 'CreateController@postForm');
                Route::get('create/finish', 'CreateController@finish');

                // Payment Gateway Success & Cancel
                Route::get('create/payment/success', 'CreateController@paymentConfirmation');
                Route::get('create/payment/cancel', 'CreateController@paymentCancel');
                Route::post('create/payment/success', 'CreateController@paymentConfirmation');

                // Email Address or Phone Number verification
                $router->pattern('field', 'email|phone');
                Route::get('verify/post/{id}/resend/email', 'CreateController@reSendVerificationEmail');
                Route::get('verify/post/{id}/resend/sms', 'CreateController@reSendVerificationSms');
                Route::get('verify/post/{field}/{token?}', 'CreateController@verification');
                Route::post('verify/post/{field}/{token?}', 'CreateController@verification');
            });

            // MultiSteps Post creation
            Route::group(['namespace' => 'CreateOrEdit\MultiSteps'], function ($router) {
                Route::get('posts/create/{tmpToken?}', 'CreateController@getForm');
                Route::post('posts/create', 'CreateController@postForm')->name('create-post');
                Route::put('posts/create/{tmpToken}', 'CreateController@postForm');
                Route::get('posts/create/{tmpToken}/photos', 'PhotoController@getForm');
                Route::post('posts/create/{tmpToken}/photos', 'PhotoController@postForm');
                Route::post('posts/create/{tmpToken}/photos/{id}/delete', 'PhotoController@delete');
                Route::get('posts/create/{tmpToken}/payment', 'PaymentController@getForm');
                Route::post('posts/create/{tmpToken}/payment', 'PaymentController@postForm');
                Route::get('posts/create/{tmpToken}/finish', 'CreateController@finish');

                // Payment Gateway Success & Cancel
                Route::get('posts/create/{tmpToken}/payment/success', 'PaymentController@paymentConfirmation');
                Route::get('posts/create/{tmpToken}/payment/cancel', 'PaymentController@paymentCancel');
                Route::post('posts/create/{tmpToken}/payment/success', 'PaymentController@paymentConfirmation');

                // Email Address or Phone Number verification
                $router->pattern('field', 'email|phone');
                Route::get('verify/post/{id}/resend/email', 'CreateController@reSendVerificationEmail');
                Route::get('verify/post/{id}/resend/sms', 'CreateController@reSendVerificationSms');
                Route::get('verify/post/{field}/{token?}', 'CreateController@verification');
                Route::post('verify/post/{field}/{token?}', 'CreateController@verification');
            });

            Route::group(['middleware' => 'auth'], function ($router) {
                $router->pattern('id', '[0-9]+');

                // SingleStep Post edition
                Route::group(['namespace' => 'CreateOrEdit\SingleStep'], function ($router) {
                    Route::get('edit/{id}', 'EditController@getForm');
                    Route::put('edit/{id}', 'EditController@postForm');

                    // Payment Gateway Success & Cancel
                    Route::get('edit/{id}/payment/success', 'EditController@paymentConfirmation');
                    Route::get('edit/{id}/payment/cancel', 'EditController@paymentCancel');
                    Route::post('edit/{id}/payment/success', 'EditController@paymentConfirmation');
                });

                // MultiSteps Post edition
                Route::group(['namespace' => 'CreateOrEdit\MultiSteps'], function ($router) {
                    Route::get('posts/{id}/edit', 'EditController@getForm');
                    Route::put('posts/{id}/edit', 'EditController@postForm');
                    Route::get('posts/{id}/photos', 'PhotoController@getForm');
                    Route::post('posts/{id}/photos', 'PhotoController@postForm');
                    Route::post('posts/{token}/photos/{id}/delete', 'PhotoController@delete');
                    Route::get('posts/{id}/payment', 'PaymentController@getForm');
                    Route::post('posts/{id}/payment', 'PaymentController@postForm');

                    // Payment Gateway Success & Cancel
                    Route::get('posts/{id}/payment/success', 'PaymentController@paymentConfirmation');
                    Route::get('posts/{id}/payment/cancel', 'PaymentController@paymentCancel');
                    Route::post('posts/{id}/payment/success', 'PaymentController@paymentConfirmation');
                });
            });

            // Post's Details
            Route::get(dynamicRoute('routes.post'), 'DetailsController@index');

            // Send report abuse
            Route::get('posts/{id}/report', 'ReportController@showReportForm');
            Route::post('posts/{id}/report', 'ReportController@sendReport');
        });

        Route::get('/company/{slug}/{tab}', [CompanyController::class, 'viewCompanyDetails'])->name('company');

        // ACCOUNT
        Route::group(['prefix' => 'account'], function ($router) {
            // Messenger
            // Contact Post's Author

            Route::group([
                'namespace' => 'Account',
                'prefix' => 'messages',
            ], function ($router) {
                Route::post('posts/{id}', 'MessagesController@store');
            });

            Route::group([
                'middleware' => ['auth', 'banned.user', 'no.http.cache'],
                'namespace' => 'Account',
            ], function ($router) {
                $router->pattern('id', '[0-9]+');

                //company
                Route::get('/company-profile', 'CompanyController@index')->name('company-profile');
                $router->pattern('pagePath', '(company-profile)+');
                Route::get('/company-profile/{id}', 'CompanyController@index');
                Route::post('/company-profile', 'CompanyController@index');
                Route::post('/company-profile/create/', 'CompanyController@create');

                //company-Payment
                Route::get('/company-payment', 'CompanyPaymentController@index')->name('company-payment');
                Route::get('/company-payment/create', 'CompanyPaymentController@create')->name('create_company_payment');
                Route::post('/company-payment/create', 'CompanyPaymentController@store')->name('save_company_payment');
                Route::get('/company-payment/edit/{id}', 'CompanyPaymentController@edit')->name('edit_company_payment');
                Route::post('/company-payment/update/{id}', 'CompanyPaymentController@update')->name('update-company-payment');
                Route::post('/account/update-show-on-invoice', 'CompanyPaymentController@updateShowOnInvoice')->name('update-show-on-invoice');
                Route::post('/company-payment/update/default-payment', 'CompanyPaymentController@updateDefaultpayment')->name('update-default-payment');
                Route::get('/company-payment/{currency}/{getcurrency?}', 'CompanyPaymentController@getForm');
                Route::delete('/company-payment/delete/{id}', 'CompanyPaymentController@destroy')->name('delete_company_payment');
                $router->pattern('pagePath', '(company-payment)+');

                //company-address
                Route::get('/company-address', 'CompanyAddressController@index')->name('company_address');
                $router->pattern('pagePath', '(company_address)+');
                Route::get('/company-address/create/', 'CompanyAddressController@create')->name('create_company_address');
                Route::post('/company-address/create/', 'CompanyAddressController@store')->name('save_company_address');
                Route::post('/company-address/update/default-address', 'CompanyAddressController@updateDefaultAddress')->name('update-default-address');
                Route::get('/company-address/edit/{id}', 'CompanyAddressController@edit')->name('edit_company_address');
                Route::post('/company-address/update/{id}', 'CompanyAddressController@update')->name('update-company-address');
                Route::delete('/company-address/delete/{id}', 'CompanyAddressController@destroy')->name('delete_company_address');

                //company-address
                Route::get('/shipping-address', 'ShippingAddressController@index')->name('shipping_address');
                $router->pattern('pagePath', '(shipping_address)+');
                Route::get('/shipping-address/create/', 'ShippingAddressController@create')->name('create_shipping_address');
                Route::post('/shipping-address/create/', 'ShippingAddressController@store')->name('save_shipping_address');
                Route::post('/shipping-address/update/default-address', 'ShippingAddressController@updateDefaultAddress')->name('update-default-shipping-address');
                Route::get('/shipping-address/edit/{id}', 'ShippingAddressController@edit')->name('edit_shipping_address');
                Route::post('/shipping-address/update/{id}', 'ShippingAddressController@update')->name('update-shipping-address');
                Route::delete('/shipping-address/delete/{id}', 'ShippingAddressController@destroy')->name('delete_shipping_address');

                // Users
                Route::get('/', 'EditController@index');
                Route::group(['middleware' => 'impersonate.protect'], function () {
                    Route::put('/', 'EditController@updateDetails');
                    Route::put('settings', 'EditController@updateSettings');
                    Route::match(array('GET', 'POST'), 'working-hours', 'EditController@workingHoursSettings')->name('working-hours');
                    Route::put('preferences', 'EditController@updatePreferences');
                    Route::post('{id}/photo', 'EditController@updatePhoto');
                    Route::post('{id}/photo/delete', 'EditController@deletePhoto');
                });
                Route::get('close', 'CloseController@index');
                Route::group(['middleware' => 'impersonate.protect'], function () {
                    Route::post('close', 'CloseController@submit');
                });

                // All Orders
                Route::get('all-orders', 'OrdersController@getOrders')->name('account.orders');
                Route::get('orders/purchased', 'OrdersController@getPurchasedOrders')->name('account.orders.purchased');
                Route::get('orders/sales', 'OrdersController@getSalesOrders')->name('account.orders.sales');
// Order Details
                Route::get('orders/{id}', 'OrdersController@showOrder')->name('account.orders.show');
                Route::get('orders/sales/{id}', 'OrdersController@showSalesOrder')->name('account.orders.sales.show');
                Route::get('orders/{id}/download-invoice', 'OrdersController@downloadPurchasedInvoice')
                    ->name('account.orders.download-invoice');

// Sales Order Invoice Download
                Route::get('orders/sales/{id}/download-invoice', 'OrdersController@downloadSalesInvoice')
                    ->name('account.orders.sales.download-invoice');
                Route::get('order-payments', 'OrdersController@showPayments')->name('account.order-payments');
                Route::get('{id}/download-invoice', 'Account\OrderController@downloadInvoice')->name('account.orders.download.invoice');
                Route::get('{id}/download-invoice', 'Account\OrderController@downloadSalesInvoice')->name('account.orders.sales.download.invoice');
                // Posts
                Route::get('saved-search', 'PostsController@getSavedSearch');
                $router->pattern('pagePath', '(my-posts|archived|favourite|pending-approval|saved-search)+');
                Route::get('{pagePath}', 'PostsController@getPage');
                Route::get('my-posts/{id}/offline', 'PostsController@getMyPosts');
                Route::get('my-posts/{id}/addons', 'PostsController@addons');
                Route::get('my-posts/{ids}/upgrade', 'PostsController@upgradePosts')->name('upgrade-post');
                Route::get('archived/{id}/repost', 'PostsController@getArchivedPosts');
                Route::get('{pagePath}/{id}/delete', 'PostsController@destroy');
                Route::post('{pagePath}/delete', 'PostsController@destroy');
                Route::delete('/delete-addons/{id}', 'PostsController@deleteAddon')->name('post-addons.delete');
                Route::post('my-posts/{ids}/payment', 'PostsController@postForm');
                Route::delete('/delete-default-addons/{id}', 'PostsController@removeDefaultAddons')->name('default-addons.delete');
                Route::post('/update-default-addons/{id}', 'PostsController@updateDefaultAddons')->name('update-default.addons');
                // Route::get('my-posts/{ids}/payment/success', 'PostsController@paymentConfirmation');
                // Route::get('my-posts/{ids}/payment/cancel', 'PostsController@paymentCancel');
                // Route::post('my-posts/{ids}/payment/success', 'PostsController@paymentConfirmation');

                Route::get('my-posts/{ids}/payment/success', 'PostsController@paymentConfirmation');
                Route::get('my-posts/{ids}/payment/cancel', 'PostsController@paymentCancel');
                Route::post('my-posts/{ids}/payment/success', 'PostsController@paymentConfirmation');

                // Messenger
                Route::group(['prefix' => 'messages'], function ($router) {
                    $router->pattern('id', '[0-9]+');
                    Route::post('check-new', 'MessagesController@checkNew');
                    Route::get('/', 'MessagesController@index');
                    // Route::get('create', 'MessagesController@create');
                    Route::post('/', 'MessagesController@store');
                    Route::get('{id}', 'MessagesController@show');
                    Route::put('{id}', 'MessagesController@update');
                    Route::get('{id}/actions', 'MessagesController@actions');
                    Route::post('actions', 'MessagesController@actions');
                    Route::post('mark-as-read', 'MessagesController@markAllAsRead');
                });

                // Transactions
                Route::get('transactions', 'TransactionsController@index');
            });
        });


        // AJAX
        Route::group(['prefix' => 'ajax'], function ($router) {
            Route::get('countries/{countryCode}/admins/{adminType}', 'Ajax\LocationController@getAdmins');
            Route::get('countries/{countryCode}/admins/{adminType}/{adminCode}/cities', 'Ajax\LocationController@getCities');
            Route::get('countries/{countryCode}/cities/{id}', 'Ajax\LocationController@getSelectedCity');
            Route::post('countries/{countryCode}/cities/autocomplete', 'Ajax\LocationController@searchedCities');
            Route::post('countries/{countryCode}/admin1/cities', 'Ajax\LocationController@getAdmin1WithCities');
            Route::post('category/select-category', 'Ajax\CategoryController@getCategoriesHtml');
            Route::post('category/custom-fields', 'Ajax\CategoryController@getCustomFields');
            Route::post('save/post', 'Ajax\PostController@savePost');
            Route::post('save/search', 'Ajax\PostController@saveSearch');
            Route::post('post/phone', 'Ajax\PostController@getPhone');
            Route::post('post/pictures/reorder', 'Ajax\PostController@picturesReorder');
            Route::post('post/update', 'Ajax\PostController@updatePost')->name('update-post-ajax');
            Route::post('post/durations', 'Ajax\PostController@updateDurations')->name('update-durations-ajax');
            Route::post('post/getDurationData', 'Ajax\PostController@fetchDurationsData')->name('fetch-durations-data');
            Route::post('post/delete-duration', 'Ajax\PostController@deleteDuration')->name('delete-duration-ajax');
            Route::post('post/getData', 'Ajax\PostController@retrieveData')->name('fetch-post-data');
            Route::post('post/store-addon', 'Ajax\PostController@updateAddon')->name('store.addon');
            Route::post('post/store-addons', 'Ajax\PostController@storeMultiAddons')->name('store.addons');
            Route::post('post/store-carts', 'Ajax\PostController@StoreCartsDetails')->name('store.carts');
            Route::post('/cart/update-quantity', 'Ajax\PostController@updateQuantity')->name('cart.updateQuantity');
        });


        // FEEDS
        Route::feeds();


        // SITEMAPS (XML)
        Route::get('{countryCode}/sitemaps.xml', 'SitemapsController@site');
        Route::get('{countryCode}/sitemaps/pages.xml', 'SitemapsController@pages');
        Route::get('{countryCode}/sitemaps/categories.xml', 'SitemapsController@categories');
        Route::get('{countryCode}/sitemaps/cities.xml', 'SitemapsController@cities');
        Route::get('{countryCode}/sitemaps/posts.xml', 'SitemapsController@posts');
        Route::get('{countryCode}/sitemaps/companies.xml', 'SitemapsController@companies');


        // PAGES
        Route::get(dynamicRoute('routes.pricing'), 'PageController@pricing');
        Route::get(dynamicRoute('routes.pageBySlug'), 'PageController@cms');
        Route::get(dynamicRoute('routes.contact'), 'PageController@contact');
        Route::post(dynamicRoute('routes.contact'), 'PageController@contactPost');

        // SITEMAP (HTML)
        Route::get(dynamicRoute('routes.sitemap'), 'SitemapController@index');

        // SEARCH
        Route::group(['namespace' => 'Search'], function ($router) {
            $router->pattern('id', '[0-9]+');
            $router->pattern('username', '[a-zA-Z0-9]+');
            Route::get(dynamicRoute('routes.search'), 'SearchController@index');
            Route::get(dynamicRoute('routes.searchPostsByUserId'), 'UserController@index');
            Route::get(dynamicRoute('routes.searchPostsByUsername'), 'UserController@profile');
            Route::get(dynamicRoute('routes.searchPostsByTag'), 'TagController@index');
            Route::get(dynamicRoute('routes.searchPostsByCity'), 'CityController@index');
            Route::get(dynamicRoute('routes.searchPostsBySubCat'), 'CategoryController@index');
            Route::get(dynamicRoute('routes.searchPostsByCat'), 'CategoryController@index');
        });
    });
});
