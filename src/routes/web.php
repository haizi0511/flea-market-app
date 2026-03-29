<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MylistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;

Route::get('/register', [UserController::class,'register']);
Route::get('/', [ItemController::class, 'index']);
Route::get('/mylist', [MylistController::class, 'mylist']);
Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'store']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->name('purchase.index');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/{item_id}/address', [PurchaseController::class, 'edit'])->name('purchase.address.edit');
    Route::post('/purchase/{item_id}/address', [PurchaseController::class, 'update'])->name('purchase.address.update');
    Route::get('/sell',[ItemController::class, 'create']);
    Route::post('/sell',[ItemController::class, 'store']);
    Route::get('/mypage',[ProfileController::class, 'index']);
    Route::post('/mypage/profile', [ProfileController::class, 'store']);
    Route::put('/mypage/profile', [ProfileController::class, 'update']);
    Route::post('/item/{item_id}/like', [MylistController::class, 'store']);
    Route::delete('/item/{item_id}/like', [MylistController::class, 'destroy']);
    Route::post('/item/{item_id}/comments', [CommentController::class, 'store']);
});

Route::get('/item/search', [ItemController::class, 'search']);
Route::get('/item/{item_id}', [ItemController::class, 'detail']);
