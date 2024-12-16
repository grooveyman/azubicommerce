<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//register user for token
Route::fallback(function () {
    return response()->json([
        'message' => 'Method not allowed or path not known',

    ], Response::HTTP_METHOD_NOT_ALLOWED);
});
Route::post('/auth/register', [AuthController::class, 'create']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

//products route
Route::resources([
    'products' => ProductController::class,
    'carts' => CartController::class,
]);

// Apply Sanctum middleware to only the 'index', 'store', 'update', and 'destroy' methods 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});


Route::get('/product/search', [ProductController::class, 'search']);
