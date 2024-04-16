<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AddressController;
use \App\Http\Controllers\CardController;
use \App\Http\Controllers\UsersController;

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

Route::get('get-districts', [AddressController::class, 'getCities']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('phone-verify', [AuthController::class, 'PhoneVerify']);
Route::post('phone-register', [AuthController::class, 'PhoneRegister']);
Route::post('login', [AuthController::class, 'login']);
Route::post('google-login-or-register', [AuthController::class, 'googleLoginOrRegister']);

Route::get('get-products-by-category', [\App\Http\Controllers\CategoryController::class, 'getProductsByCategory']);
Route::get('categories', [\App\Http\Controllers\CategoryController::class, 'getCategories'])->name('get_categories');
Route::get('subcategory/{id}', [\App\Http\Controllers\SubCategoryController::class, 'getSubcategory'])->name('get_subcategory');
Route::get('sizes-types/{id}', [\App\Http\Controllers\ProductsController::class, 'getSizes'])->name('get_sizes');
Route::get('get-products-by-categories', [\App\Http\Controllers\ProductsController::class, 'getProductsByCategories']);
Route::get('get-products-by-sub-categories/{id}', [\App\Http\Controllers\ProductsController::class, 'getProductsBySubCategories']);
Route::get('get-categories-by-product/{id}', [\App\Http\Controllers\ProductsController::class, 'getCategoriesByProduct'])->name('get_categories_by_product');
Route::get('products', [\App\Http\Controllers\ProductsController::class, 'getProducts']);
Route::get('products-by-category', [\App\Http\Controllers\ProductsController::class, 'getProductsByCategory']);
Route::get('product/{id}', [\App\Http\Controllers\ProductsController::class, 'getProduct']);
Route::get('best-seller', [\App\Http\Controllers\ProductsController::class, 'BestSeller']);
Route::post('get-favourite-products', [\App\Http\Controllers\ProductsController::class, 'getFavouriteProducts']);
Route::group(['middleware'=>['auth:sanctum', 'is_auth']], function (){

    Route::post('store-card', [CardController::class, 'storeCard']);
    Route::post('set-address', [AddressController::class, 'setAddress']);
    Route::post('edit-address', [AddressController::class, 'editAddress']);
    Route::get('get-address', [AddressController::class, 'getAddress']);
    Route::post('destroy-address', [AddressController::class, 'destroy']);
    Route::get('get-cards', [CardController::class, 'getCards']);
    Route::post('store-card', [CardController::class, 'storeCard']);
    Route::post('update-card', [CardController::class, 'updateCard']);
    Route::get('show-card', [CardController::class, 'showCard']);
    Route::post('destroy-card', [CardController::class, 'destroyCard']);
    Route::post('get-coupon', [\App\Http\Controllers\ApiOrderController::class, 'getCoupon']);
    Route::post('confirm-order', [\App\Http\Controllers\ApiOrderController::class, 'confirmOrder']);
    Route::post('get-characterized-product', [\App\Http\Controllers\ProductsController::class, 'getCharacterizedProduct']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('get-user', [AuthController::class, 'getUser']);
    Route::post('personal-information', [UsersController::class, 'setPersonalInformation']);
    Route::get('personal-information', [UsersController::class, 'getPersonalInformation']);
});
Route::post('delete-product', [\App\Http\Controllers\ProductsController::class, 'deleteProductImage']);
Route::post('delete-warehouse', [\App\Http\Controllers\CharacterizedProductsController::class, 'deleteWarehouseImage']);
