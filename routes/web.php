<?php
$prefix = session('applocale');
$types = ['homewear', 'bijoux'];

Route::get('/', function () {
    return view('/front/api');
});

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/download/image/{src}', 'PagesController@downloadImage');


Route::post('/some', function (Illuminate\Http\Request $request) {
    return ['ok' => 'ok'];
});

Route::get('/generate_pdf', 'PaymentController@generatePdf');
Route::get('/sitemap.xml', 'SitemapController@index');

Route::get('/status/{orderId}', 'Payments\Methods\Paypal@getPaymentStatus')->name('status');
Route::get('/cancel-status/{orderId}', 'Payments\Methods\Paypal@getPaymentCancelStatus')->name('cancel-status');
Route::any('/paypal/callback', 'Payments\Methods\Paypal@callBack');
Route::any('/paydo/callback', 'Payments\Methods\Paydo@callBack');

Route::any('/payment/callback', 'Payments\Paynet@callBackLink');
Route::any('/paynet/callback', 'PaymentController@paynetCallback');


// Front routes
Route::group(['prefix' => $prefix], function () use ($types) {
    Route::get('/oops', 'PagesController@getOopsPage')->name('oops');
    Route::get('/cart', 'CartController@index')->name('cart');
    Route::get('/wish', 'WishListController@index');

    // order
    Route::get('/order', 'CheckoutController@renderCheckoutShipping')->name('order');
    Route::get('/order/payment/{orderId}', 'CheckoutController@renderCheckoutPayment')->name('order-payment');
    Route::get('/thanks', 'CheckoutController@renderThankyouPage')->name('thanks');

    Route::get('/paydo/payment/success/{orderId}/{payment}', 'Payments\Methods\Paydo@getSuccessStatus')->name('paydo-success');
    Route::get('/paydo/payment/fail/{orderId}/{payment}', 'Payments\Methods\Paydo@getFailStatus')->name('paydo-fail');

    Route::get('/login/{provider}', 'AuthController@redirectToProvider');
    Route::get('/login/{provider}/callback', 'AuthController@handleProviderCallback');

    //guest user settings
    Route::post('set-user-settings', 'Controller@setUserSettings');

    Route::get('/', 'PagesController@index')->name('home');
    Route::get('/home', 'PagesController@index')->name('home');
    Route::get('/new', 'ProductsController@renderNewIn')->name('dynamic');
    Route::get('/sale', 'ProductsController@renderOutlet')->name('dynamic');
    Route::get('/promos', 'ProductsController@renderPromos')->name('dynamic');
    Route::get('/promos/prod/{id}', 'ProductsController@renderProductPromo')->name('dynamic');
    Route::get('/promos/set/{id}', 'ProductsController@renderSetPromo')->name('dynamic');
    // Static Pages
    Route::get('/{pages}', 'PagesController@getPages')->name('pages');

    foreach ($types as $key => $type) {
        Route::group(['prefix' => $type], function () {
            Route::post('/contact-feed-back', 'FeedBackController@contactFeedBack');
            Route::post('/save-country-user', 'Controller@saveCountryUser');

            Route::get('/catalog/all', 'ProductsController@categoryRenderAll')->name('dynamic');
            Route::get('/catalog/{category}', 'ProductsController@categoryRender')->name('dynamic');
            Route::get('/catalog/all', 'ProductsController@categoryRenderAll')->name('dynamic');
            Route::get('/catalog/{category}/{product}', 'ProductsController@productRender')->name('dynamic');
            // Route::get('/promotions', 'ProductsController@renderPromotions')->name('dynamic');

            Route::get('/collection/{collection}', 'ProductsController@collectionRender')->name('dynamic');

            Route::get('/logout', 'AuthController@logout');
            Route::get('/login', 'AuthController@renderLogin');
        });
    }

    // Localization
    Cache::forget('lang.js');
    Route::get('/js/lang.js', 'LanguagesController@changeLangScript')->name('assets.lang');
});


