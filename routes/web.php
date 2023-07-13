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
Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\OrdersController::class, 'mainOrders'])->name('mainOrders');
    Route::get('/create_orders/{id?}', [App\Http\Controllers\OrdersController::class, 'createOrders'])->name('createOrders');
    Route::post('/check_if_order_isset', [App\Http\Controllers\OrdersController::class, 'checkIfOrderIsset'])->name('checkIfOrderIsset');
    Route::get('/grade/{id?}', [App\Http\Controllers\GradeController::class, 'showGrade'])->name('showGrade');
    Route::get('/docs_setting', [App\Http\Controllers\GradeController::class, 'docsSetting'])->name('docsSetting');
    Route::post('/start_new_order', [App\Http\Controllers\OrdersController::class, 'startNewOrder'])->name('startNewOrder');
    Route::post('/start_get_old_order', [App\Http\Controllers\OrdersController::class, 'startGetOldOrder'])->name('startGetOldOrder');
    Route::post('/update_order', [App\Http\Controllers\OrdersController::class, 'updateOrder'])->name('updateOrder');
    Route::post('/store_xlsx', [App\Http\Controllers\OrdersController::class, 'storeXlsx'])->name('storeXlsx');
    Route::post('/store_doc', [App\Http\Controllers\OrdersController::class, 'store_doc'])->name('store_doc');
    Route::post('/store_doc_templ', [App\Http\Controllers\OrdersController::class, 'storeDocTempl'])->name('storeDocTempl');
    Route::post('/delete_orders', [App\Http\Controllers\OrdersController::class, 'deleteOrders'])->name('deleteOrders');
    Route::post('/mark_as_important', [App\Http\Controllers\OrdersController::class, 'markAsImportant'])->name('markAsImportant');
    Route::post('/important_mark', [App\Http\Controllers\OrdersController::class, 'importantMark'])->name('importantMark');
//    Route::post('/update_order_logist', [App\Http\Controllers\OrdersController::class, 'updateOrderLogist'])->name('updateOrderLogist');
    Route::post('/update_order_logist', [App\Http\Controllers\MainHeaderController::class, 'updateOrderLogist'])->name('updateOrderLogist');
    Route::post('/orderChangeColumn', [App\Http\Controllers\MainHeaderController::class, 'orderChangeColumn'])->name('orderChangeColumn');
    Route::post('/update_unread_status', [App\Http\Controllers\OrdersController::class, 'update_unread_status'])->name('update_unread_status');
    Route::post('/update_unread_status_v_rabote', [App\Http\Controllers\OrdersController::class, 'update_unread_status_v_rabote'])->name('update_unread_status_v_rabote');
    Route::post('/add_oplata_orders', [App\Http\Controllers\OrdersController::class, 'add_oplata_orders'])->name('add_oplata_orders');
    Route::post('/update_order_oplata', [App\Http\Controllers\OrdersController::class, 'update_order_oplata'])->name('update_order_oplata');
    Route::post('/delete_oplata_summa', [App\Http\Controllers\OrdersController::class, 'delete_oplata_summa'])->name('delete_oplata_summa');
    Route::post('/save_ts', [App\Http\Controllers\TSController::class, 'save_ts'])->name('save_ts');
    Route::post('/get_vid_TS_list', [App\Http\Controllers\TSController::class, 'get_vid_TS_list'])->name('get_vid_TS_list');
    Route::post('/getVoditeliList', [App\Http\Controllers\VoditelController::class, 'getVoditeliList'])->name('getVoditeliList');
    Route::post('/getTSModalList', [App\Http\Controllers\TSModalController::class, 'getTSModalList'])->name('getTSModalList');
    Route::post('/getPPList', [App\Http\Controllers\PPController::class, 'getPPList'])->name('getPPList');
    Route::post('/delete_vid_TS', [App\Http\Controllers\TSController::class, 'delete_vid_TS'])->name('delete_vid_TS');
    Route::post('/deleteFromSettings', [App\Http\Controllers\GradeController::class, 'deleteFromSettings'])->name('deleteFromSettings');
    Route::post('/update_vid_TS', [App\Http\Controllers\TSController::class, 'update_vid_TS'])->name('update_vid_TS');
    Route::post('/create_vid_TS', [App\Http\Controllers\TSController::class, 'create_vid_TS'])->name('create_vid_TS');
    Route::post('/delete_TS', [App\Http\Controllers\TSController::class, 'delete_TS'])->name('delete_TS');
    Route::post('/get_ts_list', [App\Http\Controllers\TSController::class, 'get_ts_list'])->name('get_ts_list');
    Route::post('/get_forma_list', [App\Http\Controllers\TSController::class, 'get_forma_list'])->name('get_forma_list');
    Route::post('/get_type_per_list', [App\Http\Controllers\TSController::class, 'get_type_per_list'])->name('get_type_per_list');
    Route::post('/get_terminal_list', [App\Http\Controllers\TSController::class, 'get_terminal_list'])->name('get_terminal_list');
    Route::post('/getVidTSNazvanie', [App\Http\Controllers\TSController::class, 'getVidTSNazvanie'])->name('getVidTSNazvanie');
    Route::post('/getPerevozkaNazvanie', [App\Http\Controllers\PerevozchikiController::class, 'getPerevozkaNazvanie'])->name('getPerevozkaNazvanie');
    Route::post('/getVoditelNazvanie', [App\Http\Controllers\VoditelController::class, 'getVoditelNazvanie'])->name('getVoditelNazvanie');
    Route::post('/getTSModalNazvanie', [App\Http\Controllers\TSModalController::class, 'getTSModalNazvanie'])->name('getTSModalNazvanie');
    Route::post('/getTipTSNazvanie', [App\Http\Controllers\TSModalController::class, 'getTipTSNazvanie'])->name('getTipTSNazvanie');
    Route::post('/getPPNazvanie', [App\Http\Controllers\PPController::class, 'getPPNazvanie'])->name('getPPNazvanie');
    // Route::post('/get_orders_list_new', [App\Http\Controllers\OrdersController::class, 'get_orders_list_new'])->name('get_orders_list_new');
    Route::post('/getOrderlist', [App\Http\Controllers\OrdersController::class, 'getOrderlist'])->name('getOrderlist');
    Route::post('/getOrderStatus', [App\Http\Controllers\OrdersController::class, 'getOrderStatus'])->name('getOrderStatus');
    Route::post('/checkOrderStatusName', [App\Http\Controllers\OrdersController::class, 'checkOrderStatusName'])->name('checkOrderStatusName');
    Route::post('/header_counter_orders', [App\Http\Controllers\MainHeaderController::class, 'headerCounterOrders'])->name('headerCounterOrders');
    Route::post('/get_start_data_grade', [App\Http\Controllers\GradeController::class, 'get_start_data_grade'])->name('get_start_data_grade');
    Route::post('/update_final_grade', [App\Http\Controllers\GradeController::class, 'update_final_grade'])->name('update_final_grade');
    Route::post('/add_pogruzka_grade', [App\Http\Controllers\GradeController::class, 'add_pogruzka_grade'])->name('add_pogruzka_grade');
    Route::post('/save_start_summa', [App\Http\Controllers\GradeController::class, 'save_start_summa'])->name('save_start_summa');
    Route::post('/add_new_adres_pogruzka', [App\Http\Controllers\GradeController::class, 'add_new_adres_pogruzka'])->name('add_new_adres_pogruzka');
    Route::post('/add_summ', [App\Http\Controllers\GradeController::class, 'add_summ'])->name('add_summ');
    Route::post('/deleteSumm', [App\Http\Controllers\GradeController::class, 'deleteSumm'])->name('deleteSumm');
    Route::post('/get_final_grades_data', [App\Http\Controllers\GradeController::class, 'get_final_grades_data'])->name('get_final_grades_data');
    Route::post('/deleteTSFromRightColumn', [App\Http\Controllers\GradeController::class, 'deleteTSFromRightColumn'])->name('deleteTSFromRightColumn');
    Route::post('/update_one_data', [App\Http\Controllers\GradeController::class, 'update_one_data'])->name('update_one_data');
    Route::post('/update_one_data_pogruzka', [App\Http\Controllers\GradeController::class, 'update_one_data_pogruzka'])->name('update_one_data_pogruzka');
    Route::post('/up_in_db_gruzootpravitel', [App\Http\Controllers\GradeController::class, 'up_in_db_gruzootpravitel'])->name('up_in_db_gruzootpravitel');
    Route::post('/update_one_gruzzootpravitel_from_select', [App\Http\Controllers\GradeController::class, 'update_one_gruzzootpravitel_from_select'])->name('update_one_gruzzootpravitel_from_select');
    Route::post('/update_one_data_summa', [App\Http\Controllers\GradeController::class, 'update_one_data_summa'])->name('update_one_data_summa');
    Route::post('/deleteStavka', [App\Http\Controllers\GradeController::class, 'deleteStavka'])->name('deleteStavka');
    Route::post('/store_grade_file', [App\Http\Controllers\GradeController::class, 'store_grade_file'])->name('store_grade_file');
    Route::post('/add_to_naznachenie_stavki', [App\Http\Controllers\GradeController::class, 'add_to_naznachenie_stavki'])->name('add_to_naznachenie_stavki');
    Route::post('/add_to_work', [App\Http\Controllers\GradeController::class, 'add_to_work'])->name('add_to_work');
    Route::post('/delete_file_grade', [App\Http\Controllers\GradeController::class, 'delete_file_grade'])->name('delete_file_grade');
    Route::post('/deletePogVygInTS', [App\Http\Controllers\GradeController::class, 'deletePogVygInTS'])->name('deletePogVygInTS');
    Route::post('/get_ob_budzet_down', [App\Http\Controllers\GradeController::class, 'get_ob_budzet_down'])->name('get_ob_budzet_down');
    Route::post('/get_logist_list', [App\Http\Controllers\GradeController::class, 'get_logist_list'])->name('get_logist_list');
    Route::post('/get_templ_names', [App\Http\Controllers\OrdersController::class, 'get_templ_names'])->name('get_templ_names');
    Route::get('/download_all_doc_grade/{grade_id}/{ts_id}', [App\Http\Controllers\GradeController::class, 'download_all_doc_grade'])->name('download_all_doc_grade');
    Route::post('/get_template_vars', [App\Http\Controllers\GradeController::class, 'get_template_vars'])->name('get_template_vars');
    Route::post('/addPerevozchikToStavka', [App\Http\Controllers\GradeController::class, 'addPerevozchikToStavka'])->name('addPerevozchikToStavka');
    Route::post('/get_finall_doc_pdf', [App\Http\Controllers\OrdersController::class, 'get_finall_doc_pdf'])->name('get_finall_doc_pdf');
    Route::get('/get_finall_doc_pdf_file/templates/{filename}', [App\Http\Controllers\OrdersController::class, 'get_finall_doc_pdf_file'])->name('get_finall_doc_pdf_file');
    Route::post('/download_current_doc', [App\Http\Controllers\OrdersController::class, 'download_current_doc'])->name('download_current_doc');
    Route::post('/download_xlsx_orders', [App\Http\Controllers\OrdersController::class, 'download_xlsx_orders'])->name('download_xlsx_orders');
    Route::get('/get_xlsx_file/images/orders_xlsx/{filename}', [App\Http\Controllers\OrdersController::class, 'get_xlsx_file'])->name('get_xlsx_file');
    Route::post('/get_gruzootpravitel_modal', [App\Http\Controllers\GruzootpravitelController::class, 'get_gruzootpravitel_modal'])->name('get_gruzootpravitel_modal');
    Route::post('/getPerevozkaModal', [App\Http\Controllers\PerevozchikiController::class, 'getPerevozkaModal'])->name('getPerevozkaModal');
    Route::post('/getVoditeliModal', [App\Http\Controllers\VoditelController::class, 'getVoditeliModal'])->name('getVoditeliModal');
    Route::post('/getTSModal', [App\Http\Controllers\TSModalController::class, 'getTSModal'])->name('getTSModal');
    Route::post('/getPP', [App\Http\Controllers\PPController::class, 'getPP'])->name('getPP');
    Route::post('/save_gruzootpravitel', [App\Http\Controllers\GruzootpravitelController::class, 'save_gruzootpravitel'])->name('save_gruzootpravitel');
    Route::post('/savePerevozchik', [App\Http\Controllers\PerevozchikiController::class, 'savePerevozchik'])->name('savePerevozchik');
    Route::post('/saveVoditel', [App\Http\Controllers\VoditelController::class, 'saveVoditel'])->name('saveVoditel');
    Route::post('/saveTSModal', [App\Http\Controllers\TSModalController::class, 'saveTSModal'])->name('saveTSModal');
    Route::post('/savePP', [App\Http\Controllers\PPController::class, 'savePP'])->name('savePP');
    Route::post('/store_modal_file_temp', [App\Http\Controllers\GruzootpravitelController::class, 'store_modal_file_temp'])->name('store_modal_file_temp');
    Route::post('/delete_files_modal', [App\Http\Controllers\GruzootpravitelController::class, 'delete_files_modal'])->name('delete_files_modal');
    Route::post('/delete_one_file_modal', [App\Http\Controllers\GruzootpravitelController::class, 'delete_one_file_modal'])->name('delete_one_file_modal');
    Route::get('/download_modal_file/{doc}', [App\Http\Controllers\GruzootpravitelController::class, 'download_modal_file'])->name('download_modal_file');
    Route::post('/get_BIK_BANK_api', [App\Http\Controllers\GruzootpravitelController::class, 'get_BIK_BANK_api'])->name('get_BIK_BANK_api');
    Route::post('/get_INN_api', [App\Http\Controllers\GruzootpravitelController::class, 'get_INN_api'])->name('get_INN_api');
    Route::post('/delete_gruzootpravitel', [App\Http\Controllers\GruzootpravitelController::class, 'delete_gruzootpravitel'])->name('delete_gruzootpravitel');
    Route::post('/deletePerevozka', [App\Http\Controllers\PerevozchikiController::class, 'deletePerevozka'])->name('deletePerevozka');
    Route::post('/get_gruzootpravitel_list', [App\Http\Controllers\GruzootpravitelController::class, 'get_gruzootpravitel_list'])->name('get_gruzootpravitel_list');
    Route::post('/getGruzWithName', [App\Http\Controllers\GruzootpravitelController::class, 'getGruzWithName'])->name('getGruzWithName');
    Route::post('/get_gruzootpravitel_list_front', [App\Http\Controllers\GruzootpravitelController::class, 'get_gruzootpravitel_list_front'])->name('get_gruzootpravitel_list_front');
    Route::post('/get_perevozka_list_front', [App\Http\Controllers\PerevozchikiController::class, 'getPerevozkaListFront'])->name('getPerevozkaListFront');
    Route::post('/get_gruzootpravitel_list_atocomplite', [App\Http\Controllers\GruzootpravitelController::class, 'get_gruzootpravitel_list_atocomplite'])->name('get_gruzootpravitel_list_atocomplite');
    Route::post('/check_if_name_gruz', [App\Http\Controllers\GruzootpravitelController::class, 'check_if_name_gruz'])->name('check_if_name_gruz');
    Route::post('/check_if_name_gruz_isset', [App\Http\Controllers\GruzootpravitelController::class, 'check_if_name_gruz_isset'])->name('check_if_name_gruz_isset');
    Route::post('/select_gruzootpravitel', [App\Http\Controllers\GruzootpravitelController::class, 'select_gruzootpravitel'])->name('select_gruzootpravitel');
    Route::get('/gruzootpravitel', [App\Http\Controllers\GruzootpravitelController::class, 'gruzootpravitel'])->name('gruzootpravitel');
    Route::post('/get_perevozka_list', [App\Http\Controllers\OrdersController::class, 'get_perevozka_list'])->name('get_perevozka_list');
    Route::post('/getVidPerevozki', [App\Http\Controllers\OrdersController::class, 'getVidPerevozki'])->name('getVidPerevozki');
    Route::post('/check_buttons_show', [App\Http\Controllers\OrdersController::class, 'check_buttons_show'])->name('check_buttons_show');
    Route::post('/up_gruzoot_from_select', [App\Http\Controllers\OrdersController::class, 'up_gruzoot_from_select'])->name('up_gruzoot_from_select');
    Route::post('/update_perevozka_settings', [App\Http\Controllers\SettingsController::class, 'update_perevozka_settings'])->name('update_perevozka_settings');
    Route::post('/delete_perevozka_settings', [App\Http\Controllers\SettingsController::class, 'delete_perevozka_settings'])->name('delete_perevozka_settings');
    Route::post('/deleteVidPerevozka', [App\Http\Controllers\SettingsController::class, 'deleteVidPerevozka'])->name('deleteVidPerevozka');
//    Route::post('/delete_forma_settings', [App\Http\Controllers\SettingsController::class, 'delete_forma_settings'])->name('delete_forma_settings');
    Route::post('/add_perevozka_settings', [App\Http\Controllers\SettingsController::class, 'add_perevozka_settings'])->name('add_perevozka_settings');
    Route::post('/addVidPerevozka', [App\Http\Controllers\SettingsController::class, 'addVidPerevozka'])->name('addVidPerevozka');
    Route::post('/updateVidPerevozka', [App\Http\Controllers\SettingsController::class, 'updateVidPerevozka'])->name('updateVidPerevozka');
    Route::post('/add_forma_settings', [App\Http\Controllers\SettingsController::class, 'add_forma_settings'])->name('add_forma_settings');
    Route::post('/get_forma_gruzootpravitel_list', [App\Http\Controllers\SettingsController::class, 'get_forma_gruzootpravitel_list'])->name('get_forma_gruzootpravitel_list');
    Route::post('/update_user', [App\Http\Controllers\SettingsController::class, 'update_user'])->name('update_user');
    Route::post('/update_forma_settings', [App\Http\Controllers\SettingsController::class, 'update_forma_settings'])->name('update_forma_settings');
    Route::post('/logout', [App\Http\Controllers\ProfileController::class, 'logout'])->name('logout');
    Route::post('/searchBack', [App\Http\Controllers\SearchController::class, 'searchBack'])->name('searchBack');
    Route::post('/searchBackStavkiInput', [App\Http\Controllers\SearchController::class, 'searchBackStavkiInput'])->name('searchBackStavkiInput');
    Route::post('/searchBackKompaniya', [App\Http\Controllers\SearchController::class, 'searchBackKompaniya'])->name('searchBackKompaniya');
    Route::post('/searchBackStavki', [App\Http\Controllers\SearchController::class, 'searchBackStavki'])->name('searchBackStavki');
    Route::post('/searchBackTS', [App\Http\Controllers\SearchController::class, 'searchBackTS'])->name('searchBackTS');
    Route::post('/searchBackTSStavki', [App\Http\Controllers\SearchController::class, 'searchBackTSStavki'])->name('searchBackTSStavki');


    Route::get('/users_list', [App\Http\Controllers\AdminController::class, 'users_list'])->name('users_list');
    Route::post('/get_users_list', [App\Http\Controllers\AdminController::class, 'get_users_list'])->name('get_users_list');
    Route::post('/change_permission', [App\Http\Controllers\AdminController::class, 'change_permission'])->name('change_permission');
    Route::post('/is_admin_settings', [App\Http\Controllers\AdminController::class, 'is_admin_settings'])->name('is_admin_settings');
    Route::post('/get_roles', [App\Http\Controllers\AdminController::class, 'get_roles'])->name('get_roles');
    Route::post('/getRoleUser', [App\Http\Controllers\AdminController::class, 'getRoleUser'])->name('getRoleUser');
    Route::post('/getImpalaAdres', [App\Http\Controllers\AdminController::class, 'getImpalaAdres'])->name('getImpalaAdres');
    Route::post('/updateAdres', [App\Http\Controllers\AdminController::class, 'updateAdres'])->name('updateAdres');

    Route::post('/add_user', [App\Http\Controllers\AdminController::class, 'add_user'])->name('add_user');
    Route::get('/perevozchiki', [App\Http\Controllers\PerevozchikiController::class, 'perevozchiki'])->name('perevozchiki');
    Route::get('/stavki', [App\Http\Controllers\StavkiController::class, 'stavki'])->name('stavki');
    Route::post('/getStavkiList', [App\Http\Controllers\StavkiController::class, 'getStavkiList'])->name('getStavkiList');

});
Auth::routes();
//
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
