<?php

use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\ShoppingListItemController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('list')->group(function () {
    Route::post('/', [ShoppingListController::class, 'store']);
    Route::get('/all', [ShoppingListController::class, 'getAllLists']);
    Route::get('/{listId}', [ShoppingListController::class, 'getListWithItems']);
});

Route::prefix('items')->group(function () {
    Route::get('/', [ShoppingListItemController::class, 'index']);
    Route::post('/', [ShoppingListItemController::class, 'store']);
    Route::put('/{id}', [ShoppingListItemController::class, 'update']);
    Route::delete('/{id}', [ShoppingListItemController::class, 'destroy']);
    Route::patch('/{id}/toggle-picked-up', [ShoppingListItemController::class, 'togglePickedUp']);
    // Route::put('/reorder', [ShoppingListItemController::class, 'reorderItems']); // attempt at reorder but did not have enough time
});
