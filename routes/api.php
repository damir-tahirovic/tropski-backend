<?php

use App\Http\Controllers\Api\ExtraGroupExtraPivotController;
use App\Http\Controllers\Api\ExtraTranController;
use App\Http\Controllers\Api\HotelLanguageController;
use App\Http\Controllers\Api\HotelUserController;
use App\Http\Controllers\Api\ItemTranController;
use App\Http\Controllers\Api\ItemTypeTranController;
use App\Http\Controllers\Api\OrderPlaceController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RoleHotelUserController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CategoryTranController;
use App\Http\Controllers\Api\ExtraController;
use App\Http\Controllers\Api\ExtraGroupController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\MainCategoryController;
use App\Http\Controllers\Api\MainCategoryTranController;
use App\Http\Controllers\Api\ItemTypeController;

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


//Rute za UserController

Route::post('/register', [UserController::class, 'createUser'])->name('create-user');
Route::post('/login', [UserController::class, 'loginUser'])->name('login-user');
Route::get('users', [UserController::class, 'index'])->name('users.index');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Rute za MainCategory

Route::get('main-categories', [MainCategoryController::class, 'index'])->name('main-categories.index');
Route::post('main-categories', [MainCategoryController::class, 'store'])->name('main-categories.store');
Route::get('main-categories/{id}', [MainCategoryController::class, 'show'])->name('main-categories.show');
Route::put('main-categories/{id}', [MainCategoryController::class, 'update'])->name('main-categories.update');
Route::delete('main-categories/{id}', [MainCategoryController::class, 'destroy'])->name('main-categories.delete');

//Route::get('main-categories-all', [MainCategoryController::class, 'mainCategoriesWithCategories'])->name('main-categories.mainCategoriesWithCategories');


//Rute za Category

Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

//Route::get('categories-all', [CategoryController::class, 'categoriesWithSubcategories'])->name('categories.categoriesWithSubcategories');


//Rute za Hotel

Route::get('hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::post('hotels', [HotelController::class, 'store'])->name('hotels.store');
Route::get('hotels/{id}', [HotelController::class, 'show'])->name('hotels.show');
Route::put('hotels/{id}', [HotelController::class, 'update'])->name('hotels.update');
Route::delete('hotels/{id}', [HotelController::class, 'destroy'])->name('hotels.destroy');

Route::get('hotels-main-categories', [HotelController::class, 'allHotelsWithMainCategories'])->name('hotels.allHotelsWithMainCategories');
Route::get('hotels-main-categories/{id}', [HotelController::class, 'hotelsWithMainCategories'])->name('hotels.hotelsWithMainCategories');
Route::get('hotels-languages', [HotelController::class, 'allHotelsWithLanguages'])->name('hotels.allHotelsWithLanguages');


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


//Rute za Language

Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');
Route::post('languages', [LanguageController::class, 'store'])->name('languages.store');
Route::get('languages/{id}', [LanguageController::class, 'show'])->name('languages.show');
Route::put('languages/{id}', [LanguageController::class, 'update'])->name('languages.update');
Route::delete('languages/{id}', [LanguageController::class, 'destroy'])->name('languages.destroy');


//Rute za Item

Route::get('items', [ItemController::class, 'index'])->name('items.index');
Route::post('items/with-one-option', [ItemController::class, 'storeWithOneOption'])->name('items.store-with-one-option');
Route::post('items', [ItemController::class, 'store'])->name('items.store');
Route::get('items/{id}', [ItemController::class, 'show'])->name('items.show');
Route::put('items/{id}', [ItemController::class, 'update'])->name('items.update');
Route::delete('items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

Route::get('items-by-category/{id}', [ItemController::class, 'itemsByCategory'])->name('items.items-by-category');


//Rute za MainCategoryTran

Route::get('main-category-trans', [MainCategoryTranController::class, 'index'])->name('main-category-trans.index');
Route::post('main-category-trans', [MainCategoryTranController::class, 'store'])->name('main-category-trans.store');
Route::get('main-category-trans/{id}', [MainCategoryTranController::class, 'show'])->name('main-category-trans.show');
Route::put('main-category-trans/{id}', [MainCategoryTranController::class, 'update'])->name('main-category-trans.update');
Route::delete('main-category-trans/{id}', [MainCategoryTranController::class, 'destroy'])->name('main-category-trans.destroy');

Route::get('main-category-trans-languages', [MainCategoryTranController::class, 'mainCategoryNamesWithLanguages'])->name('main-category-trans.mainCategoryNamesWithLanguages');


//Rute za CategoryTran

Route::get('category-trans', [CategoryTranController::class, 'index'])->name('category-trans.index');
Route::post('category-trans', [CategoryTranController::class, 'store'])->name('category-trans.store');
Route::get('category-trans/{id}', [CategoryTranController::class, 'show'])->name('category-trans.show');
Route::put('category-trans/{id}', [CategoryTranController::class, 'update'])->name('category-trans.update');
Route::delete('category-trans/{id}', [CategoryTranController::class, 'destroy'])->name('category-trans.destroy');


//Rute za ItemType

Route::get('item-types', [ItemTypeController::class, 'index'])->name('item-types.index');
Route::post('item-types', [ItemTypeController::class, 'store'])->name('item-types.store');
Route::post('item-types-with-latest-item', [ItemTypeController::class, 'storeWithLatestItem'])->name('item-types.store-with-latest-item');
Route::get('item-types/{id}', [ItemTypeController::class, 'show'])->name('item-types.show');
Route::put('item-types/{id}', [ItemTypeController::class, 'update'])->name('item-types.update');
Route::delete('item-types/{id}', [ItemTypeController::class, 'destroy'])->name('item-types.destroy');


//Rute za ItemTypeTran

Route::get('item-type-trans', [ItemTypeTranController::class, 'index'])->name('item-type-trans.index');
Route::post('item-type-trans', [ItemTypeTranController::class, 'store'])->name('item-type-trans.store');
Route::get('item-type-trans/{id}', [ItemTypeTranController::class, 'show'])->name('item-type-trans.show');
Route::put('item-type-trans/{id}', [ItemTypeTranController::class, 'update'])->name('item-type-trans.update');
Route::delete('item-type-trans/{id}', [ItemTypeTranController::class, 'destroy'])->name('item-type-trans.destroy');


//Rute za ItemTran

Route::get('item-trans', [ItemTranController::class, 'index'])->name('item-trans.index');
Route::post('item-trans', [ItemTranController::class, 'store'])->name('item-trans.store');
Route::get('item-trans/{id}', [ItemTranController::class, 'show'])->name('item-trans.show');
Route::put('item-trans/{id}', [ItemTranController::class, 'update'])->name('item-trans.update');
Route::delete('item-trans/{id}', [ItemTranController::class, 'destroy'])->name('item-trans.destroy');


//Rute za ExtraTran

Route::get('extra-trans', [ExtraTranController::class, 'index'])->name('extra-trans.index');
Route::post('extra-trans', [ExtraTranController::class, 'store'])->name('extra-trans.store');
Route::get('extra-trans/{id}', [ExtraTranController::class, 'show'])->name('extra-trans.show');
Route::put('extra-trans/{id}', [ExtraTranController::class, 'update'])->name('extra-trans.update');
Route::delete('extra-trans/{id}', [ExtraTranController::class, 'destroy'])->name('extra-trans.destroy');


//Rute za ExtraGroupExtraPivot

Route::get('extra-group-extra-pivots', [ExtraGroupExtraPivotController::class, 'index'])->name('extra-group-extra-pivots.index');
Route::post('extra-group-extra-pivots', [ExtraGroupExtraPivotController::class, 'store'])->name('extra-group-extra-pivots.store');
Route::get('extra-group-extra-pivots/{id}', [ExtraGroupExtraPivotController::class, 'show'])->name('extra-group-extra-pivots.show');
Route::put('extra-group-extra-pivots/{id}', [ExtraGroupExtraPivotController::class, 'update'])->name('extra-group-extra-pivots.update');
Route::delete('extra-group-extra-pivots/{id}', [ExtraGroupExtraPivotController::class, 'destroy'])->name('extra-group-extra-pivots.destroy');


//Rute za OrderPlace

Route::get('order-places', [OrderPlaceController::class, 'index'])->name('order-places.index');
Route::post('order-places', [OrderPlaceController::class, 'store'])->name('order-places.store');
Route::get('order-places/{id}', [OrderPlaceController::class, 'show'])->name('order-places.show');
Route::put('order-places/{id}', [OrderPlaceController::class, 'update'])->name('order-places.update');
Route::delete('order-places/{id}', [OrderPlaceController::class, 'destroy'])->name('order-places.destroy');


//Rute za HotelUser

Route::get('hotel-users', [HotelUserController::class, 'index'])->name('hotel-users.index');
Route::post('hotel-users', [HotelUserController::class, 'store'])->name('hotel-users.store');
Route::get('hotel-users/{id}', [HotelUserController::class, 'show'])->name('hotel-users.show');
Route::put('hotel-users/{id}', [HotelUserController::class, 'update'])->name('hotel-users.update');
Route::delete('hotel-users/{id}', [HotelUserController::class, 'destroy'])->name('hotel-users.destroy');


//Rute za Role

Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('roles/{id}', [RoleController::class, 'show'])->name('roles.show');
Route::put('roles/{id}', [RoleController::class, 'update'])->name('roles.update');
Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');


//Rute za RoleHotelUser

Route::get('role-hotel-users', [RoleHotelUserController::class, 'index'])->name('role-hotel-users.index');
Route::post('role-hotel-users', [RoleHotelUserController::class, 'store'])->name('role-hotel-users.store');
Route::get('role-hotel-users/{id}', [RoleHotelUserController::class, 'show'])->name('role-hotel-users.show');
Route::put('role-hotel-users/{id}', [RoleHotelUserController::class, 'update'])->name('role-hotel-users.update');
Route::delete('role-hotel-users/{id}', [RoleHotelUserController::class, 'destroy'])->name('role-hotel-users.destroy');


//Rute za HotelLanguages

Route::get('hotel-languages', [HotelLanguageController::class, 'index'])->name('role-hotel-languages.index');
Route::post('hotel-languages', [HotelLanguageController::class, 'store'])->name('role-hotel-languages.store');
Route::get('hotel-languages/{id}', [HotelLanguageController::class, 'show'])->name('role-hotel-languages.show');
Route::put('hotel-languages/{id}', [HotelLanguageController::class, 'update'])->name('role-hotel-languages.update');
Route::delete('hotel-languages/{id}', [HotelLanguageController::class, 'destroy'])->name('role-hotel-languages.destroy');

