<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ColorController;
use \App\Http\Controllers\SizesController;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\CouponController;
use \App\Http\Controllers\ProductsController;
use \App\Http\Controllers\CharacterizedProductsController;
use \App\Http\Controllers\SubCategoryController;
use \App\Http\Controllers\SubSubCategoryController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\DiscountController;
use \App\Http\Controllers\UsersController;

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

Auth::routes();

Route::group(['middleware'=>['isAdmin', 'language']], function(){
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('color', ColorController::class);
    Route::resource('size', SizesController::class);
//    Route::resource('order', OrderController::class);
    Route::resource('product', ProductsController::class);
//    Route::get('products-category', [ProductsController::class, 'category'])->name('product.category');
    Route::get('products-by-category/{id}', [ProductsController::class, 'product'])->name('product.category.product');
    Route::resource('characterizedProducts', CharacterizedProductsController::class);
    Route::get('characterized-products-category', [CharacterizedProductsController::class, 'category'])->name('characterizedProducts.category');
    Route::get('product-by-category/{id}', [CharacterizedProductsController::class, 'product'])->name('characterizedProducts.category.product');
    Route::get('characterized-product-by-category/{id}', [CharacterizedProductsController::class, 'characterizedProduct'])->name('characterizedProducts.category.characterized_product');
    Route::get('create-characterized-product-by-category/{id}', [CharacterizedProductsController::class, 'createCharacterizedProduct'])->name('characterizedProducts.category.create_characterized_product');

    Route::get('get-user', [UsersController::class, 'getUser'])->name('getUser');
    Route::post('change-language', [HomeController::class, 'changeLanguage'])->name('language.change');
    Route::resource('coupons', CouponController::class);
    Route::resource('discount', DiscountController::class);
    Route::resource('user', UsersController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('subcategory', SubCategoryController::class);
    Route::get('sub-category', [SubCategoryController::class, 'category'])->name('subcategory.category');
    Route::get('sub-category/subcategory/{id}', [SubCategoryController::class, 'subcategory'])->name('subcategory.subcategory');
    Route::resource('subsubcategory', SubSubCategoryController::class);
    Route::get('sub-sub-category', [SubSubCategoryController::class, 'category'])->name('subsubcategory.category');
    Route::get('sub-sub-category/subcategory/{id}', [SubSubCategoryController::class, 'subcategory'])->name('subsubcategory.subcategory');
    Route::get('sub-sub-category/subsubcategory/{id}', [SubSubCategoryController::class, 'subsubcategory'])->name('subsubcategory.subsubcategory');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/set-cities', [HomeController::class, 'setCities']);

    Route::get('/index', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order-category', [OrderController::class, 'category'])->name('order.category');
    Route::get('/show/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::get('/destroy/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
});

Route::get('/welcome', [App\Http\Controllers\HomeController::class, 'welcome'])->name('welcome');


