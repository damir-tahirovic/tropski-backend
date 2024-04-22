<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CategoryTranController;
use App\Http\Controllers\Api\ExtraController;
use App\Http\Controllers\Api\ExtraGroupController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\MainCategoryController;
use App\Models\Item;
use App\Models\MainCategory;

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

//Rute za MainCategory

Route::get('main-categories', [MainCategoryController::class, 'index'])->name('main-categories.index');
Route::post('main-categories', [MainCategoryController::class, 'store'])->name('main-categories.store');
Route::get('main-categories/{id}', [MainCategoryController::class, 'show'])->name('main-categories.show');
Route::put('main-categories/{id}', [MainCategoryController::class, 'update'])->name('main-categories.update');
Route::delete('main-categories/{id}', [MainCategoryController::class, 'destroy'])->name('main-categories.delete');

Route::get('main-categories-all', [MainCategoryController::class, 'mainCategoriesWithCategories'])->name('main-categories.mainCategoriesWithCategories');

//Rute za Category

Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

//Rute za Hotel

Route::get('hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::post('hotels', [HotelController::class, 'store'])->name('hotels.store');
Route::get('hotels/{id}', [HotelController::class, 'show'])->name('hotels.show');
Route::put('hotels/{id}', [HotelController::class, 'update'])->name('hotels.update');
Route::delete('hotels/{id}', [HotelController::class, 'destroy'])->name('hotels.destroy');

//Rute za Extra

Route::get('extras', [ExtraController::class, 'index'])->name('extras.index');
Route::post('extras', [ExtraController::class, 'store'])->name('extras.store');
Route::get('extras/{id}', [ExtraController::class, 'show'])->name('extras.show');
Route::put('extras/{id}', [ExtraController::class, 'update'])->name('extras.update');
Route::delete('extras/{id}', [ExtraController::class, 'destroy'])->name('extras.destroy');

//Rute za ExtraGroup

Route::get('extra-groups', [ExtraGroupController::class, 'index'])->name('extra-groups.index');
Route::post('extra-groups', [ExtraGroupController::class, 'store'])->name('extra-groups.store');
Route::get('extra-groups/{id}', [ExtraGroupController::class, 'show'])->name('extra-groups.show');
Route::put('extra-groups/{id}', [ExtraGroupController::class, 'update'])->name('extra-groups.update');
Route::delete('extra-groups/{id}', [ExtraGroupController::class, 'destroy'])->name('extra-groups.destroy');
