<?php

use Illuminate\Support\Facades\Auth;
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

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::get('/orders',  [App\Http\Controllers\OrdersController::class, 'main_orders'])->name('main_orders');
Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\OrdersController::class, 'main_orders'])->name('main_orders');
    Route::get('/create_orders/{id?}', [App\Http\Controllers\OrdersController::class, 'create_orders'])->name('create_orders');
    Route::get('/grade/{id?}', [App\Http\Controllers\GradeController::class, 'show_grade'])->name('show_grade');
    Route::get('/docs_setting', [App\Http\Controllers\GradeController::class, 'docs_setting'])->name('docs_setting');
    Route::post('/start_new_order', [App\Http\Controllers\OrdersController::class, 'start_new_order'])->name('start_new_order');
    Route::post('/start_get_old_order', [App\Http\Controllers\OrdersController::class, 'start_get_old_order'])->name('start_get_old_order');
    Route::post('/update_order', [App\Http\Controllers\OrdersController::class, 'update_order'])->name('update_order');
    Route::post('/store_xlsx', [App\Http\Controllers\OrdersController::class, 'store_xlsx'])->name('store_xlsx');
    Route::post('/set_doc', [App\Http\Controllers\OrdersController::class, 'set_doc'])->name('set_doc');
    Route::post('/store_doc', [App\Http\Controllers\OrdersController::class, 'store_doc'])->name('store_doc');
    Route::post('/store_doc_templ', [App\Http\Controllers\OrdersController::class, 'store_doc_templ'])->name('store_doc_templ');
    Route::post('/delete_orders', [App\Http\Controllers\OrdersController::class, 'delete_orders'])->name('delete_orders');
    Route::post('/mark_as_important', [App\Http\Controllers\OrdersController::class, 'mark_as_important'])->name('mark_as_important');
    Route::post('/save_ts', [App\Http\Controllers\TSController::class, 'save_ts'])->name('save_ts');
    Route::post('/delete_TS', [App\Http\Controllers\TSController::class, 'delete_TS'])->name('delete_TS');
    Route::post('/get_ts_list', [App\Http\Controllers\TSController::class, 'get_ts_list'])->name('get_ts_list');
    Route::post('/get_forma_list', [App\Http\Controllers\TSController::class, 'get_forma_list'])->name('get_forma_list');
    Route::post('/get_type_per_list', [App\Http\Controllers\TSController::class, 'get_type_per_list'])->name('get_type_per_list');
    Route::post('/get_terminal_list', [App\Http\Controllers\TSController::class, 'get_terminal_list'])->name('get_terminal_list');
    Route::post('/get_orders_list_new', [App\Http\Controllers\OrdersController::class, 'get_orders_list_new'])->name('get_orders_list_new');
    Route::post('/get_start_data_grade', [App\Http\Controllers\GradeController::class, 'get_start_data_grade'])->name('get_start_data_grade');
    Route::post('/update_final_grade', [App\Http\Controllers\GradeController::class, 'update_final_grade'])->name('update_final_grade');
    Route::post('/add_pogruzka_grade', [App\Http\Controllers\GradeController::class, 'add_pogruzka_grade'])->name('add_pogruzka_grade');
    Route::post('/save_start_summa', [App\Http\Controllers\GradeController::class, 'save_start_summa'])->name('save_start_summa');
    Route::post('/add_new_adres_pogruzka', [App\Http\Controllers\GradeController::class, 'add_new_adres_pogruzka'])->name('add_new_adres_pogruzka');
    Route::post('/add_summ', [App\Http\Controllers\GradeController::class, 'add_summ'])->name('add_summ');
    Route::post('/get_final_grades_data', [App\Http\Controllers\GradeController::class, 'get_final_grades_data'])->name('get_final_grades_data');
    Route::post('/update_one_data', [App\Http\Controllers\GradeController::class, 'update_one_data'])->name('update_one_data');
    Route::post('/update_one_data_pogruzka', [App\Http\Controllers\GradeController::class, 'update_one_data_pogruzka'])->name('update_one_data_pogruzka');
    Route::post('/update_one_data_summa', [App\Http\Controllers\GradeController::class, 'update_one_data_summa'])->name('update_one_data_summa');
    Route::post('/store_grade_file', [App\Http\Controllers\GradeController::class, 'store_grade_file'])->name('store_grade_file');
    Route::post('/delete_file_grade', [App\Http\Controllers\GradeController::class, 'delete_file_grade'])->name('delete_file_grade');
    Route::post('/get_ob_budzet_down', [App\Http\Controllers\GradeController::class, 'get_ob_budzet_down'])->name('get_ob_budzet_down');
    Route::post('/get_templ_names', [App\Http\Controllers\OrdersController::class, 'get_templ_names'])->name('get_templ_names');
    Route::get('/download_all_doc_grade/{grade_id}/{ts_id}', [App\Http\Controllers\GradeController::class, 'download_all_doc_grade'])->name('download_all_doc_grade');
    Route::post('/get_template_vars', [App\Http\Controllers\GradeController::class, 'get_template_vars'])->name('get_template_vars');
    Route::post('/get_finall_doc_pdf', [App\Http\Controllers\OrdersController::class, 'get_finall_doc_pdf'])->name('get_finall_doc_pdf');
    Route::get('/get_finall_doc_pdf_file/templates/{filename}', [App\Http\Controllers\OrdersController::class, 'get_finall_doc_pdf_file'])->name('get_finall_doc_pdf_file');
    Route::post('/download_current_doc', [App\Http\Controllers\OrdersController::class, 'download_current_doc'])->name('download_current_doc');
    Route::post('/download_xlsx_orders', [App\Http\Controllers\OrdersController::class, 'download_xlsx_orders'])->name('download_xlsx_orders');
    Route::get('/get_xlsx_file/images/orders_xlsx/{filename}', [App\Http\Controllers\OrdersController::class, 'get_xlsx_file'])->name('get_xlsx_file');
    Route::post('/get_gruzootpravitel_modal', [App\Http\Controllers\GruzootpravitelController::class, 'get_gruzootpravitel_modal'])->name('get_gruzootpravitel_modal');
    Route::post('/save_gruzootpravitel', [App\Http\Controllers\GruzootpravitelController::class, 'save_gruzootpravitel'])->name('save_gruzootpravitel');
    Route::post('/store_modal_file_temp', [App\Http\Controllers\GruzootpravitelController::class, 'store_modal_file_temp'])->name('store_modal_file_temp');
    Route::post('/delete_files_modal', [App\Http\Controllers\GruzootpravitelController::class, 'delete_files_modal'])->name('delete_files_modal');
    Route::post('/delete_one_file_modal', [App\Http\Controllers\GruzootpravitelController::class, 'delete_one_file_modal'])->name('delete_one_file_modal');
    Route::get('/download_modal_file/{doc}', [App\Http\Controllers\GruzootpravitelController::class, 'download_modal_file'])->name('download_modal_file');
    Route::post('/get_BIK_BANK_api', [App\Http\Controllers\GruzootpravitelController::class, 'get_BIK_BANK_api'])->name('get_BIK_BANK_api');
    Route::post('/get_INN_api', [App\Http\Controllers\GruzootpravitelController::class, 'get_INN_api'])->name('get_INN_api');
    Route::post('/delete_gruzootpravitel', [App\Http\Controllers\GruzootpravitelController::class, 'delete_gruzootpravitel'])->name('delete_gruzootpravitel');
    Route::post('/get_gruzootpravitel_list', [App\Http\Controllers\GruzootpravitelController::class, 'get_gruzootpravitel_list'])->name('get_gruzootpravitel_list');
    Route::post('/select_gruzootpravitel', [App\Http\Controllers\GruzootpravitelController::class, 'select_gruzootpravitel'])->name('select_gruzootpravitel');
    Route::post('/get_perevozka_list', [App\Http\Controllers\OrdersController::class, 'get_perevozka_list'])->name('get_perevozka_list');
    Route::post('/update_perevozka_settings', [App\Http\Controllers\SettingsController::class, 'update_perevozka_settings'])->name('update_perevozka_settings');
    Route::post('/delete_perevozka_settings', [App\Http\Controllers\SettingsController::class, 'delete_perevozka_settings'])->name('delete_perevozka_settings');
//    Route::post('/delete_forma_settings', [App\Http\Controllers\SettingsController::class, 'delete_forma_settings'])->name('delete_forma_settings');
    Route::post('/add_perevozka_settings', [App\Http\Controllers\SettingsController::class, 'add_perevozka_settings'])->name('add_perevozka_settings');
    Route::post('/add_forma_settings', [App\Http\Controllers\SettingsController::class, 'add_forma_settings'])->name('add_forma_settings');
    Route::post('/get_forma_gruzootpravitel_list', [App\Http\Controllers\SettingsController::class, 'get_forma_gruzootpravitel_list'])->name('get_forma_gruzootpravitel_list');
    Route::post('/update_forma_settings', [App\Http\Controllers\SettingsController::class, 'update_forma_settings'])->name('update_forma_settings');


    Route::get('/users_list', [App\Http\Controllers\AdminController::class, 'users_list'])->name('users_list');
    Route::post('/get_users_list', [App\Http\Controllers\AdminController::class, 'get_users_list'])->name('get_users_list');
    Route::post('/change_permission', [App\Http\Controllers\AdminController::class, 'change_permission'])->name('change_permission');
    Route::post('/is_admin_settings', [App\Http\Controllers\AdminController::class, 'is_admin_settings'])->name('is_admin_settings');

    Route::post('/add_user', [App\Http\Controllers\AdminController::class, 'add_user'])->name('add_user');

});
Auth::routes();
//
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
