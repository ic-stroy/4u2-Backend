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
use \App\Http\Controllers\LanguageController;
use \App\Http\Controllers\PickUpController;

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
    Route::resource('pick_up', PickUpController::class);
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
//    Route::post('change-language', [HomeController::class, 'changeLanguage'])->name('language.change');
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

    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order-category', [OrderController::class, 'category'])->name('order.category');
    Route::get('/order/show/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::get('/order/destroy/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::get('/finished-all-orders', [OrderController::class, 'finishedAllOrders'])->name('order.finished_all_orders');
    Route::post('/address', [OrderController::class, 'address'])->name('order.address');
    Route::post('/accepted-by-recipient/{id}', [OrderController::class, 'acceptedByRecipient'])->name('accepted_by_recipient');
    Route::post('/cancell-accepted-by-recipient/{id}', [OrderController::class, 'cancellAcceptedByRecipient'])->name('cancell_accepted_by_recipient');
    Route::delete('/order-detail/cancell/{id}', [OrderController::class, 'cancellOrderDetail'])->name('cancell_order_detail');
    Route::post('/order-detail/perform/{id}', [OrderController::class, 'performOrderDetail'])->name('perform_order_detail');

    Route::group(['prefix' => 'language'], function () {
        Route::get('/', [LanguageController::class, 'index'])->name('language.index');
        Route::get('/language/show/{id}', [LanguageController::class, 'show'])->name('language.show');
        Route::post('/translation/save/', [LanguageController::class, 'translation_save'])->name('translation.save');
        Route::post('/language/change/', [LanguageController::class, 'changeLanguage'])->name('language.change');
        Route::post('/env_key_update', [LanguageController::class, 'env_key_update'])->name('env_key_update.update');
        Route::get('/language/create/', [LanguageController::class, 'create'])->name('languages.create');
        Route::post('/language/added/', [LanguageController::class, 'store'])->name('languages.store');
        Route::get('/language/edit/{id}', [LanguageController::class, 'languageEdit'])->name('language.edit');
        Route::put('/language/update/{id}', [LanguageController::class, 'update'])->name('language.update');
        Route::delete('/language/delete/{id}', [LanguageController::class, 'languageDestroy'])->name('language.destroy');
        Route::post('/language/update/value', [LanguageController::class, 'updateValue'])->name('languages.update_value');
    });
});

Route::get('/welcome', [App\Http\Controllers\HomeController::class, 'welcome'])->name('welcome');
Route::post('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');


