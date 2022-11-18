<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::post('/en/api/expert', 'API\v2\ProductController@createProduct');

//Route::post('/api/some', function (\http\Client\Request $request) {
//    return [];
//});

Route::post('/en/api/book', 'API\OfferController@submitBook');

Route::group(['prefix' => 'api'], function () {

    Route::get('categories', 'API\v2\CategoryController@index');

    Route::get('category', 'API\v2\CategoryController@getBySlug');

    Route::get('category/{id}', 'API\v2\CategoryController@getById');

    Route::get('experts', 'API\v2\ProductController@index');

    Route::get('experts/search', 'API\v2\ProductController@search');

    Route::get('experts/featured', 'API\v2\ProductController@getFeatured');

    Route::get('expert', 'API\v2\ProductController@getBySlug');

    Route::get('expert/{id}', 'API\v2\ProductController@getById');


    Route::post('expert', 'API\v2\ProductController@createProduct');

});



Route::group(['prefix' => 'api_'], function () {
    Route::get('settings', 'API\SettingsController@getSettings');
    Route::get('translations', 'API\SettingsController@getTranslations');
    Route::get('banners', 'API\SettingsController@getBanners');
    Route::get('static-pages', 'API\SettingsController@getStaticPages');

    Route::get('categories', 'API\ProductsController@getCategories');
    Route::get('category', 'API\ProductsController@getCategory');
    Route::get('marketplace', 'API\ProductsController@getMarketplaceCategory');
    Route::get('product', 'API\ProductsController@getProduct');


    Route::get('products/new', 'API\ProductsController@getNewProducts');
    Route::get('products/outlet', 'API\ProductsController@getOutletProducts');
    Route::get('products/all', 'API\ProductsController@getAllProducts');

    Route::get('products/sort', 'API\ProductsController@getSortedProducts');
    Route::get('products/filter', 'API\ProductsController@getFiltredProducts');
    Route::get('products/default-filter', 'API\ProductsController@getDefaultFilter');

    Route::get('collections', 'API\ProductsController@getCollections');
    Route::get('collection', 'API\ProductsController@getCollection');

    Route::get('promotions', 'API\ProductsController@getPromotions');

    Route::get('set/cart', 'API\CheckoutController@setCart'); // Remake to post

    Route::get('cart', 'API\CheckoutController@getCart');

    Route::get('designers', 'API\ProductsController@getDesigners');
    Route::get('designer', 'API\ProductsController@getDesigner');
});

Route::group(['prefix' => 'api/v2', 'middleware' => 'cors'], function () {
    Route::get('categories', 'Api\ProductsController@getCategories');

    Route::get('data', 'Api\ServiceController@initData');

    Route::get('translations', 'API\TranslationsController@all');

    Route::get('promotions', 'Api\PromotionController@get');

    Route::get('leads', 'Api\ServiceController@addLeads');  // to remake POST
});
