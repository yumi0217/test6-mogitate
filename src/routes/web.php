<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SeasonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/products/register', [SeasonController::class, 'getRegister']);
Route::post('/product/upload', [ProductController::class, 'upload']);
Route::get('/products/detail/{product_id}', [ProductController::class, 'getDetail']);
Route::get('/products/search', [ProductController::class, 'getSearch']);
Route::post('/products/search', [ProductController::class, 'postSearch']);
Route::get('/products/{product_id}/delete', [ProductController::class, 'postDelete']);
Route::post('/products/update', [ProductController::class, 'postUpdate']);