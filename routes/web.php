<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CollectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('products/', [ProductController::class, 'index']); // Lấy danh sách products
Route::get('products/data', [ProductController::class, 'getData']); // Lấy danh sách products với filter
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('products/by-collection/{id}', [ProductController::class, 'getByCollectionId']);

Route::get('collections/', [CollectionController::class, 'index']);
Route::get('collections/{id}', [CollectionController::class, 'show']);

