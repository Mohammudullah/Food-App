<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\UserController;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use Carbon\Carbon;

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


Route::get('/', [HomeController::class, 'index']);

Route::resources([
    'users' => UserController::class,
    'items' => ItemController::class,
    'menus' => MenuController::class,
    'menu-items' => MenuItemController::class,
]);

Route::post('/items/{id}/change-status', [ItemController::class, 'change_status']);
Route::post('/menus/{id}/change-status', [MenuController::class, 'change_status']);

//cart
Route::put('/cart', [HomeController::class, 'cart']);


