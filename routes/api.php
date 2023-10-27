<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group([
    'namespace' => '\App\Http\Controllers',
    'prefix' => 'lista-sub',
], function () {
    Route::get('/get-lista-sub', 'ListaSubstituicaoController@getListSub');
    Route::get('/get-html-lista-sub', 'ListaSubstituicaoController@getHtmlListSub');
});

Route::group([
    'namespace' => '\App\Http\Controllers',
    'prefix' => 'alimentos',
], function () {
    Route::get('/get-alimentos', 'AlimentosController@getAlimentos');
});
