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

Route::post('/otp/send', 'UserController@sendOtp');
Route::post('/otp/get', 'UserController@getOtp');

Route::post('/otp/change-number/send', 'UserController@changeNumberSendOtp');
Route::post('/otp/change-number/get', 'UserController@changeNumberGetOtp');

//Route::resource('providers', 'ProviderController');
Route::get('/providers', 'ProviderController@index')->middleware('auth:api');
Route::get('/providers/create', 'ProviderController@create')->middleware('auth:api');
Route::get('/providers/{provider}', 'ProviderController@show')->middleware('auth:api');
Route::post('/providers', 'ProviderController@store')->middleware('auth:api');
Route::get('/providers/{provider}/edit', 'ProviderController@edit');
Route::post('/providers/{provider}', 'ProviderController@update');
Route::delete('/providers/{provider}', 'ProviderController@I')->middleware('auth:api');


//Route::resource('customers', 'CustomerController');
Route::get('/customers', 'CustomerController@index')->middleware('auth:api');
Route::get('/customers/create', 'CustomerController@create')->middleware('auth:api');
Route::get('/customers/{customer}', 'CustomerController@show')->middleware('auth:api');
Route::post('/customers', 'CustomerController@store')->middleware('auth:api');
Route::get('/customers/{customer}/edit', 'CustomerController@edit');
Route::post('/customers/{customer}', 'CustomerController@update');
Route::delete('/customers/{customer}', 'CustomerController@destroy')->middleware('auth:api');

Route::resource('service-images', 'ServiceImageController');
Route::resource('product-images', 'ProductImageController');
Route::get('/product-images', 'ProductImageController@index')->middleware('auth:api');
Route::get('/product-images', 'ProductImageController@create')->middleware('auth:api');
Route::get('/product-images/{product_image}', 'ProductImageController@show')->middleware('auth:api');
Route::post('/product-images', 'ProductImageController@store')->middleware('auth:api');
Route::get('/product-images/{product_image}/edit', 'ProductImageController@edit');
Route::post('/product-images/{product_image}', 'ProductImageController@update');
Route::delete('/product-images/{product_image}', 'ProductImageController@destroy')->middleware('auth:api');

Route::resource('/users', 'UserController')->middleware('auth:api');
Route::resource('/services', 'ServiceController')->middleware('auth:api');
Route::resource('/products', 'ProductController')->middleware('auth:api');
Route::resource('/service-ratings', 'ServiceRatingController')->middleware('auth:api');
Route::resource('/product-ratings', 'ProductRatingController')->middleware('auth:api');
Route::resource('/identification-types', 'IdentificationTypeController')->middleware('auth:api');
Route::resource('/banners', 'BannerController')->middleware('auth:api');
Route::resource('/faqs', 'FaqController')->middleware('auth:api');
Route::resource('/service-categories', 'ServiceCategoryController')->middleware('auth:api');
Route::resource('/product-categories', 'ProductCategoryController')->middleware('auth:api');
Route::resource('/ride-histories', 'RideHistoryController')->middleware('auth:api');
Route::resource('/ride-stops', 'RideStopController')->middleware('auth:api');
Route::post('/sub-service-categories', 'ServiceCategoryController@subServiceCategories')->middleware('auth:api');
Route::post('/sub-product-categories', 'ProductCategoryController@subProductCategories')->middleware('auth:api');
Route::post('/sub-services', 'ServiceController@subServices')->middleware('auth:api');
Route::post('/sub-products', 'ProductController@subProducts')->middleware('auth:api');
Route::post('/filtered-services', 'ServiceController@filteredServices')->middleware('auth:api');
Route::post('/filtered-products', 'ProductController@filteredProducts')->middleware('auth:api');
Route::resource('/service-categories', 'ServiceCategoryController')->middleware('auth:api');
Route::resource('/carts', 'CartController')->middleware('auth:api');
Route::post('/customer-home-data', 'UserController@fetchCustomerHomeData')->middleware('auth:api');
Route::post('/provider-home-data', 'UserController@fetchProviderHomeData')->middleware('auth:api');
Route::post('/chat-data', 'UserController@chatData')->middleware('auth:api');
Route::post('/provider-chat-data', 'UserController@providerChatData')->middleware('auth:api');
Route::post('/group-call', 'UserController@groupCall')->middleware('auth:api');
Route::post('/pickup-address-and-nearby-cars', 'UserController@pickupAddressAndNearbyCars')->middleware('auth:api');
Route::post('/nearest-rider', 'UserController@nearestRider')->middleware('auth:api');
Route::post('/user-phone-number', 'UserController@userPhoneNumber')->middleware('auth:api');
Route::post('/provider-order-id', 'UserController@providerOrderId')->middleware('auth:api');
Route::post('/scoped-service-images', 'ServiceImageController@scopedServiceImages')->middleware('auth:api');
Route::post('/scoped-product-images', 'ProductImageController@scopedProductImages')->middleware('auth:api');
Route::post('/scoped-carts', 'CartController@scopedCarts')->middleware('auth:api');
Route::post('/scoped-provider-carts', 'CartController@scopedProviderCarts')->middleware('auth:api');
Route::post('/scoped-customer-carts', 'CartController@scopedCustomerCarts')->middleware('auth:api');
Route::post('/scoped-cart-products', 'CartProductController@scopedCartProducts')->middleware('auth:api');
Route::post('/cart-total', 'CartProductController@scopedCartTotal')->middleware('auth:api');
Route::post('/cart-total-count', 'CartProductController@scopedCartTotalCount')->middleware('auth:api');
Route::resource('/cart-products', 'CartProductController')->middleware('auth:api');
Route::resource('/payment-methods', 'PaymentMethodController')->middleware('auth:api');
Route::resource('/provider-edu-certs', 'ProviderEduCertController')->middleware('auth:api');
Route::resource('/chats', 'ChatController')->middleware('auth:api');
Route::post('/scoped-chats', 'ChatController@scopedChats')->middleware('auth:api');
Route::post('/scoped-latest-chats', 'ChatController@scopedLatestChats')->middleware('auth:api');
Route::post('/pay', 'PaymentController@pay');
Route::post('/payments/callback', 'PaymentController@callback');
Route::resource('/payments', 'PaymentController')->middleware('auth:api');
Route::post('/trips-made', 'RideHistoryController@tripsMade');
Route::post('/scoped-ride-stops', 'RideStopController@scopedRideStops')->middleware('auth:api');
Route::post('/pending-ride', 'RideHistoryController@pendingRide')->middleware('auth:api');
Route::post('/cancel-latest-pending-ride', 'RideHistoryController@cancelLatestPendingRide')->middleware('auth:api');
Route::post('/unfinished-ride-check', 'RideHistoryController@unfinishedRideCheck')->middleware('auth:api');
