<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CollectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!composer require laravel/passport
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    // Route::get('/products', 'ProductController@index');
    // Route::get('/products/{id}', 'ProductController@show');
    // Route::get('/products/by-collection', [ProductController::class, 'getByCollectionId']);
    // Route::get('/products/search-by-name', [ProductController::class, 'searchByName']);
    // Route::post('/products', 'ProductController@store');
    // Route::put('/products/{id}', 'ProductController@update');
    // Route::delete('/products/{id}', 'ProductController@destroy');
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']); // Lấy danh sách products
        Route::get('/data', [ProductController::class, 'getData']); // Lấy danh sách products với filter
        Route::post('/', [ProductController::class, 'store']); // Tạo mới một product
        Route::get('/{id}', [ProductController::class, 'show']); // Hiển thị thông tin một product cụ thể
        Route::get('/by-collection', [ProductController::class, 'getByCollectionId']);
        Route::get('/search-by-name', [ProductController::class, 'searchByName']);
        Route::post('/update/{id}', [ProductController::class, 'update']); // Cập nhật thông tin một product
        Route::delete('/delete/{id}', [ProductController::class, 'destroy']); // Xóa một product
    });
    
    // collection api url
    Route::prefix('collections')->group(function () {
        Route::get('/', [CollectionController::class, 'index']); // Lấy danh sách collections
        Route::get('/data', [CollectionController::class, 'getData']); // Lấy danh sách collections với filter
        Route::post('/', [CollectionController::class, 'store']); // Tạo mới một collection
        Route::get('/{id}', [CollectionController::class, 'show']); // Hiển thị thông tin một collection cụ thể
        Route::post('/update/{id}', [CollectionController::class, 'update']); // Cập nhật thông tin một collection
        Route::delete('/delete/{id}', [CollectionController::class, 'destroy']); // Xóa một collection
    });
});
