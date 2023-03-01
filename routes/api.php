<?php

use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'], function ($router)
    {
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/register', [LoginController::class, 'register']);
    }
);  

Route::group([
    'middleware' => 'api',
    'prefix' => 'category'], function ($router)
    {
        Route::get('/category-product', [CategoryProductController::class, 'index']);
        Route::get('/category-product/show/{id}', [CategoryProductController::class, 'show']);
        Route::POST('/category-product/store', [CategoryProductController::class, 'store']);
        Route::post('/category-product/update/{id}', [CategoryProductController::class, 'update']);
        Route::delete('/category-product/destroy/{id}', [CategoryProductController::class, 'destroy']);
    }  
);

Route::group([
    'middleware' => 'api',
    'prefix' => 'product'], function ($router)
    {
        Route::get('/product', [ProductController::class, 'index']);
        Route::POST('/store', [ProductController::class, 'store']);
        Route::get('/show/{id}', [ProductController::class, 'show']);
        Route::POST('/update/{id}', [ProductController::class, 'update']);
        Route::delete('/destroy/{id}', [ProductController::class, 'destroy']);
    }  
);