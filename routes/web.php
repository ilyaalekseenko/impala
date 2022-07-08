<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/orders',  [App\Http\Controllers\OrdersController::class, 'main_orders'])->name('main_orders');
Route::get('/create_orders/{id?}',  [App\Http\Controllers\OrdersController::class, 'create_orders'])->name('create_orders');
Route::post('/start_new_order',  [App\Http\Controllers\OrdersController::class, 'start_new_order'])->name('start_new_order');
Route::post('/start_get_old_order',  [App\Http\Controllers\OrdersController::class, 'start_get_old_order'])->name('start_get_old_order');
Route::post('/update_order',  [App\Http\Controllers\OrdersController::class, 'update_order'])->name('update_order');
Route::post('/store_xlsx',  [App\Http\Controllers\OrdersController::class, 'store_xlsx'])->name('store_xlsx');
Route::post('/delete_orders',  [App\Http\Controllers\OrdersController::class, 'delete_orders'])->name('delete_orders');
Route::post('/mark_as_important',  [App\Http\Controllers\OrdersController::class, 'mark_as_important'])->name('mark_as_important');
Route::post('/save_ts',  [App\Http\Controllers\TSController::class, 'save_ts'])->name('save_ts');
Route::post('/delete_TS',  [App\Http\Controllers\TSController::class, 'delete_TS'])->name('delete_TS');
Route::post('/get_ts_list',  [App\Http\Controllers\TSController::class, 'get_ts_list'])->name('get_ts_list');
Route::post('/get_type_per_list',  [App\Http\Controllers\TSController::class, 'get_type_per_list'])->name('get_type_per_list');
Route::post('/get_orders_list_new',  [App\Http\Controllers\OrdersController::class, 'get_orders_list_new'])->name('get_orders_list_new');
