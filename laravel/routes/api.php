<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemoController; // ★重要：司令塔を呼び出す
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 1. 一覧取得 (GET)
Route::get('/memos', [MemoController::class, 'index']);

// 2. 新規保存 (POST)
Route::post('/memos', [MemoController::class, 'store']);

// 3. メモ削除 (DELETE)
Route::delete('/memos/{id}', [MemoController::class, 'destroy']);