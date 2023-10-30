<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'middleware' => ['authAdmin'],
    'prefix' => 'admin',
    'namespace' => '\App\Http\Controllers'
], function () {
    Route::get('login', 'AdminController@index')->name('login-admin');
    Route::get('/', 'AdminController@redirectToLoginAdmin');

    Route::post('login', 'AdminController@login')->name('login-admin-post');
    Route::get('login-keycloak', 'AdminController@loginKeycloak')->name('login-admin-keycloak');
    Route::get('logout', 'AdminController@logout')->name('logout-admin');


Route::group([
    'prefix' => 'products',
    'namespace' => '\App\Http\Controllers'
], function () {
    Route::get('/', 'ProductsController@index')->name('products');
    Route::get('/product-quick-registration', 'ProductsController@productQuickRegistration')->name('product-quick-registration');
    Route::get('/product-full-registration', 'ProductsController@productFullRegistration')->name('product-full-registration');
    Route::get('/product-entry', 'ProductsController@productEntry')->name('product-entry');
    Route::get('/product-output', 'ProductsController@productOutput')->name('product-output');
    Route::get('/get-category', 'ProductsController@getCategory')->name("get-category");
    Route::get('/get-products', 'ProductsController@getProducts')->name("get-products");
    Route::get('/get-products-admin', 'ProductsController@getProductsAdmin')->name("get-products-admin");
    Route::get('/search-products-admin', 'ProductsController@searchProducts')->name("search-products-admin");
    Route::get('/search-products-in-stock', 'ProductsController@searchProductsInStock')->name("search-products-in-stock");
    Route::get('/data-product', 'ProductsController@getDadosProductsById')->name('data-product');
    Route::post('/insert-products', 'ProductsController@insertProducts')->name('insert-products');
    Route::post('/update-products', 'ProductsController@updateProduct')->name('update-products');
    Route::post('/delete-product', 'ProductsController@deleteAlimento')->name('delete-product');

    Route::get('/product-category-registration', 'CategoryController@productCategoryRegistration')->name('product-category-registration');
    Route::post('/insert-category', 'CategoryController@insertCategory')->name('insert-category');


});
Route::group([
    'prefix' => 'dash',
    'namespace' => '\App\Http\Controllers'
], function () {
    Route::get('/', 'DashController@index')->name('dash');
    Route::get('/get-total-stock', 'ProductsController@getTotalStock')->name('get-total-stock');
    Route::get('/get-total-entry-stock', 'ProductsController@getTotalEntryStock')->name('get-total-entry-stock');
    Route::get('/get-total-out-stock', 'ProductsController@getTotalOutStock')->name('get-total-out-stock');
    Route::get('/get-total-value-out-stock', 'ProductsController@getTotalValueOutStock')->name('get-total-value-out-stock');
    Route::get('/get-total-category-stock', 'DashController@getTotalCategoryStock')->name('get-total-category-stock');
});

    Route::group([
        'prefix' => 'reports',
        'namespace' => '\App\Http\Controllers'
    ], function () {
        Route::get('/', 'ReportsController@index')->name('reports');

    });

});
