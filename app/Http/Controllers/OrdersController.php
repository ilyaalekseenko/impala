<?php

namespace App\Http\Controllers;

use App\Events\DeleteOrderEvent;
use App\Events\UpdateLogistEvent;
use App\Events\UpdateNaznachenieStavkiHeaderEvent;
use App\Models\DocsTemplate;
use App\Models\FinalGrade;
use App\Models\Gruzootpravitel;
use App\Models\OplataOrders;
use App\Models\Orders;
use App\Models\Perevozka;
use App\Models\PogruzkaTS;
use App\Models\Role;
use App\Models\TemplateVar;
use App\Models\TS;
use App\Models\LogistName;
use App\Models\UnreadHeader;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Gotenberg\Gotenberg;
use Gotenberg\Stream;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\Border;
//use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpWord\PhpWord;
use Silverslice\ExcelTemplate\Template;
use Dompdf\Dompdf;

class OrdersController extends Controller
{
    public function main_orders()
    {
        $user = User::find(Auth::id());
        //получаем список ролей
        $roles= Role::find($user->roles[0]['id']);
        $permissions_arr=[];
        foreach ($roles->permissions as $permission) {
            array_push($permissions_arr, $permission['id']);
        }
        $role_perm=['role'=>$user->roles[0]['id'],'permissions'=>$permissions_arr];
        $user['role_perm']=$role_perm;
        return view('front.orders')->with('auth_user',  $user);
    }
    public function create_orders()
    {
        $user = User::find(Auth::id());
        //получаем список ролей
        $roles= Role::find($user->roles[0]['id']);
        $permissions_arr=[];
        foreach ($roles->permissions as $permission) {
            array_push($permissions_arr, $permission['id']);
        }
        $role_perm=['role'=>$user->roles[0]['id'],'permissions'=>$permissions_arr];
        $user['role_perm']=$role_perm;
        return view('front.create_orders')->with('auth_user',  $user);
    }
    public function check_if_order_isset(Request $request)
    {
        $id=$request->input('id');
        $isset = Orders::where('id', $id)->first();

        if ($isset !== null) {
            return response()->json([
                'status' => 'success',
                'data' =>'isset',
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => 'success',
                'data' =>'not',
            ], 200);
        }

    }
    public function get_perevozka_list()
    {
        $perevozka = Perevozka::all();
        return response()->json([
            'status' => 'success',
            'perevozka_list' =>$perevozka,
        ], 200);
    }
    public function check_buttons_show(Request $request)
    {
        $id=$request->input('id');
        $user_id=$request->input('user_id');
        $role=$request->input('role');
        $utverzdenie_show_button=false;
        $v_rabote_show_button=false;
        //Получим заявку
        $order=Orders::where('id',$id)->get();

        $naznachenie_stavki=$order[0]['naznachenie_stavki'];
        $v_rabote=$order[0]['v_rabote'];
        //если логист и если колонка оценка то показываем кнопку Утверждение
        if(($role==2)&&($naznachenie_stavki==null)&&($v_rabote==null))
        {
            $utverzdenie_show_button=true;
        }
        if(($role==1)&&($naznachenie_stavki==1)&&($v_rabote==null))
        {
            $v_rabote_show_button=true;
        }
        return response()->json([
            'status' => 'success',
            'utverzdenie_show_button' =>$utverzdenie_show_button,
            'v_rabote_show_button' =>$v_rabote_show_button
        ], 200);

    }
    public function download_current_doc(Request $request)
    {
        $doc_type=$request->input('doc_type');
        $doc= DocsTemplate::where('doc_type','=',$doc_type)->get();
        return response()->json([
            'status' => 'success',
            'file' =>$doc[0]['doc_name'],
        ], 200);

    }
    public function update_unread_status(Request $request)
    {
        //id = order_id
        $id=$request->input('id');
        $column_name=$request->input('column_name');
        //logist = logist_id
        $logist=$request->input('logist_id');
        if($column_name=='ocenka')
        {
            $isset= UnreadHeader::where('order_id','=',$id)->where('logist_id',$logist)->where('column_name','ocenka')->get();
            if (!$isset->isEmpty())
            {
                $isset= UnreadHeader::where('order_id','=',$id)->where('logist_id',$logist)->where('column_name','ocenka')->delete();
                $orders_count_new=UnreadHeader::where('logist_id',$logist)->where('column_name','ocenka')->count();
                broadcast(new UpdateLogistEvent(0,0,$orders_count_new,$logist))->toOthers();
            }
        }
        if($column_name=='naznachenie_stavki')
        {
            $isset= UnreadHeader::where('order_id','=',$id)->where('column_name','naznachenie_stavki')->get();
            if (!$isset->isEmpty())
            {
                $isset= UnreadHeader::where('order_id','=',$id)->where('column_name','naznachenie_stavki')->delete();
                $orders_count_new=UnreadHeader::where('column_name','naznachenie_stavki')->count();
                broadcast(new UpdateNaznachenieStavkiHeaderEvent())->toOthers();
            }
        }

        return response()->json([
            'status' => 'success',
        ], 200);

    }
    public function update_unread_status_v_rabote(Request $request)
    {
        $id=$request->input('id');
        $column_name=$request->input('column_name');
        $logist=$request->input('logist_id');
        if($column_name=='v_rabote')
        {
            $isset= UnreadHeader::where('order_id','=',$id)->where('logist_id',$logist)->where('column_name','v_rabote')->get();
            if (!$isset->isEmpty())
            {
                $isset= UnreadHeader::where('order_id','=',$id)->where('logist_id',$logist)->where('column_name','v_rabote')->delete();
                $orders_count_new=UnreadHeader::where('logist_id',$logist)->where('column_name','v_rabote')->count();
                broadcast(new UpdateLogistEvent(0,0,$orders_count_new,$logist))->toOthers();
            }
        }
    }
    public function update_order_logist(Request $request)
    {
        //id = order_id
        $id=$request->input('id');
        //logist = logist_id
        $logist=$request->input('logist');

//        $orders_count это НЕ ПРОЧИТАННЫЕ заявки

        //назначаем логиста на заявку
        if($logist!==0)
        {
            //получаем id предыдущего логиста
            $old_logist_id=Orders::where('id',$id)->get();
            //если не было предыдущего логиста
            if ($old_logist_id[0]['logist']==null) {
                //тогда назначем предыдущему логисту id ноль
                $old_logist_id=0;
            }
            else
            {
                //если был предыдущий логист тогда получаем его id
                $old_logist_id=$old_logist_id[0]['logist'];
            }
            //читал ли предыдущий логист эту заявку
            $unread_flag=UnreadHeader::where('order_id',$id)->where('column_name','ocenka')->get();
            //если эта заявка не была прочитана предыдущим логистом
            if (!$unread_flag->isEmpty()) {
                //удаляем не прочитанную заявку предыдущего логиста
                UnreadHeader::where('order_id','=',$id)->delete();
            }
            //считаем сколько не прочитанных заявок у предыдущего логиста
            $old_logist_unread=UnreadHeader::where('logist_id',$old_logist_id)->where('column_name','ocenka')->count();
            //ставим на эту заявку нового логиста
            Orders::where('id', '=', $id)->update([
                'logist' =>$logist,
            ]);
        //назначем её не прочитанной новому логисту
            UnreadHeader::firstOrCreate([
            'logist_id' =>$logist,
            'column_name' =>'ocenka',
            'order_id' =>$id,
        ]);
        //пересчитываем не прочитанные заявки у нового логиста
        $new_logist_orders_count=UnreadHeader::where('logist_id',$logist)->where('column_name','ocenka')->count();
        // UpdateLogistEvent('не прочитанные заявки нового логиста','id нового логиста','не прочитанные заявки старого логиста','id старого логиста')
        broadcast(new UpdateLogistEvent($new_logist_orders_count,$logist,$old_logist_unread,$old_logist_id))->toOthers();
        }
        //если приходит то что убрали логиста с заявки вообще ну тоесть ноль
        else
        {
            //получаем id предыдущего логиста
            $old_logist_id=Orders::where('id',$id)->get();
            //если не было предыдущего логиста
            if ($old_logist_id[0]['logist']==null) {
                //тогда назначем предыдущему логисту id ноль
                $old_logist_id=0;
            }
            else
            {
                //если был предыдущий логист тогда получаем его id
                $old_logist_id=$old_logist_id[0]['logist'];
            }
            //читал ли предыдущий логист эту заявку
            $unread_flag=UnreadHeader::where('order_id',$id)->where('column_name','ocenka')->get();
            //если эта заявка не была прочитана предыдущим логистом
            if (!$unread_flag->isEmpty()) {
                //удаляем не прочитанную заявку предыдущего логиста
                UnreadHeader::where('order_id','=',$id)->delete();
            }
            //считаем сколько не прочитанных заявок у предыдущего логиста
            $old_logist_unread=UnreadHeader::where('logist_id',$old_logist_id)->where('column_name','ocenka')->count();
            //ставим на эту заявку нового логиста
            Orders::where('id', '=', $id)->update([
                'logist' =>$logist,
            ]);
            //пересчитываем не прочитанные заявки у нового логиста
            // UpdateLogistEvent('не прочитанные заявки нового логиста','id нового логиста','не прочитанные заявки старого логиста','id старого логиста')
            broadcast(new UpdateLogistEvent(0,0,$old_logist_unread,$old_logist_id))->toOthers();

//            //проверка был ли логист назначенный до текущего назначения Не прочитанный
//            $logist_to_del=UnreadHeader::where('order_id',$id)->where('column_name','ocenka')->get();
//            //если не был
//            if ($logist_to_del->isEmpty())
//            {
//                //номер логиста ноль потому что не был назначен
//                $logist_to_del=0;
//                //соответственно у него нет оставшихся заявок
//                $orders_count_new=0;
//            }
//            //если был назначенный логист
//            else
//            {
//            UnreadHeader::where('order_id','=',$id)->delete();
//            //считаем количество не прочитанных заявок у данного логиста
//            $orders_count_new=UnreadHeader::where('logist_id',$logist_to_del[0]['logist_id'])->where('column_name','ocenka')->count();
//            //забираем id логиста
//            $logist_to_del=$logist_to_del[0]['logist_id'];
//            }
//            broadcast(new UpdateLogistEvent(0,0,$orders_count_new,$logist_to_del))->toOthers();
        }
        return response()->json([
            'status' => 'success',
        ], 200);

    }
    public function update_order(Request $request)
    {
        $id=$request->input('id');
        $data_vneseniya=$request->input('data_vneseniya');
        $rasschitat_do=$request->input('rasschitat_do');
        $nomenklatura=$request->input('nomenklatura');
        $nomer_zayavki=$request->input('nomer_zayavki');
        $kompaniya_zakazchik=$request->input('kompaniya_zakazchik');
        $menedzer_zakazchik=$request->input('menedzer_zakazchik');
        $ISD=$request->input('ISD');
        $cena_kontrakta=$request->input('cena_kontrakta');
        $data_kontrakta=$request->input('data_kontrakta');
        $adres_pogruzke=$request->input('adres_pogruzke');
        $data_pogruzki=$request->input('data_pogruzki');
        $data_dostavki=$request->input('data_dostavki');
        $adres_vygruski=$request->input('adres_vygruski');
        $komment_1=$request->input('komment_1');
        $logist=$request->input('logist');
        $gruzomesta_big=$request->input('gruzomesta_big');
        $gruzomesta_small=$request->input('gruzomesta_small');
        $rasstojanie=$request->input('rasstojanie');
        $ob_ves=$request->input('ob_ves');
        $ob_ob=$request->input('ob_ob');
        $vid_perevozki=$request->input('vid_perevozki');
        Orders::where('id', '=', $id)->update([
            'data_vneseniya' =>$data_vneseniya,
            'rasschitat_do' =>$rasschitat_do,
            'nomenklatura' =>$nomenklatura,
            'nomer_zayavki' =>$nomer_zayavki,
            'kompaniya_zakazchik' =>$kompaniya_zakazchik,
            'menedzer_zakazchik' =>$menedzer_zakazchik,
            'ISD' =>$ISD,
            'cena_kontrakta' =>$cena_kontrakta,
            'data_kontrakta' =>$data_kontrakta,
            'adres_pogruzke' =>$adres_pogruzke,
            'data_pogruzki' =>$data_pogruzki,
            'data_dostavki' =>$data_dostavki,
            'adres_vygruski' =>$adres_vygruski,
            'komment_1' =>$komment_1,
            'logist' =>$logist,
            'gruzomesta_big' =>$gruzomesta_big,
            'gruzomesta_small' =>$gruzomesta_small,
            'rasstojanie' =>$rasstojanie,
            'ob_ves' =>$ob_ves,
            'ob_ob' =>$ob_ob,
            'vid_perevozki' =>$vid_perevozki,
        ]);


    }
    public function update_order_oplata(Request $request)
    {
        $id=$request->input('id');
        $data=$request->input('data');
        $summ_opl=$request->input('summ_opl');
        OplataOrders::where('id', '=', $id)->update([
            $summ_opl =>$data,
        ]);

    }
    public function add_oplata_orders(Request $request)
    {
        $id=$request->input('id');

       $oplata_id= OplataOrders::create([
            'order_id' =>$id,
            'oplata' =>'',
            'summa' =>'',
        ]);
        return response()->json([
            'status' => 'success',
            'message' =>'оплата успешно создана',
            'data' =>$oplata_id,
        ], 200);
    }
    public function delete_oplata_summa(Request $request)
    {
        $id=$request->input('id');
        OplataOrders::where('id','=',$id)->delete();
        return response()->json([
            'status' => 'success',
            'message' =>'оплата успешно удалена'
        ], 200);

    }
    //если заявка уже существует
    public function start_get_old_order(Request $request)
    {
        $id = $request->input('id');
        $orders_list = Orders::where('id', '=', $id) ->get();
        //адрес погрузки
        if($orders_list[0]['adres_pogruzke']==null)
        {
            $adres_pogruzke_show='';
        }
        else
        {
            $adres_pogruzke_show = Gruzootpravitel::where('id', '=', $orders_list[0]['adres_pogruzke']) ->get();
            $adres_pogruzke_show = $adres_pogruzke_show[0]['nazvanie'];
        }
        //адрес выгрузки
        if($orders_list[0]['adres_vygruski']==null)
        {
            $adres_vygruski_show='';
        }
        else
        {
            $adres_vygruski_show = Gruzootpravitel::where('id', '=', $orders_list[0]['adres_vygruski']) ->get();
            $adres_vygruski_show = $adres_vygruski_show[0]['nazvanie'];
        }
        //адрес выгрузки
        if($orders_list[0]['adres_vygruski']==null)
        {
            $adres_vygruski_show='';
        }
        else
        {
            $adres_vygruski_show = Gruzootpravitel::where('id', '=', $orders_list[0]['adres_vygruski']) ->get();
            $adres_vygruski_show = $adres_vygruski_show[0]['nazvanie'];
        }
        $oplata_list= OplataOrders::where('order_id', '=', $id) ->get();
        $arr_to_add=[];
       foreach ($oplata_list as $oplata)
       {
           array_push($arr_to_add, $oplata);
       }
        $orders_list[0]['oplata']=$arr_to_add;

        $log_name= User::where('id', '=', $orders_list[0]['logist'])->get();

       if (!$log_name->isEmpty())
            {
                $orders_list[0]['logist_name']=$log_name[0]['last_name'].' '.$log_name[0]['first_name'].' '.$log_name[0]['patronymic'];
            }
            else
            {
                $orders_list[0]['logist_name']='Логист не выбран';
            }
       //получаем список ТС
        $TS_list= TS::where('order_id', '=', $id)->get();
        //получаем список адресов погрузки и выгрузки
        foreach ($TS_list as $ts)
        {
            $TS_list_pogruzka = PogruzkaTS::where('order_id', '=', $id)->where('pogruzka_or_vygruzka', '1')->where('id_ts', $ts['id_ts'])->get();
            $TS_list_vygruzka = PogruzkaTS::where('order_id', '=', $id)->where('pogruzka_or_vygruzka', '2')->where('id_ts', $ts['id_ts'])->get();
            $ts['adres_pogruzki_TS']=$TS_list_pogruzka;
            $ts['adres_vygr_TS']=$TS_list_vygruzka;
            //добавляем название к адресу погрузки
            foreach ($ts['adres_pogruzki_TS'] as $one_adres) {
                if ($one_adres['adres_pogruzki'] == null) {
                    $one_adres['adres_pogruzke_show'] = '';
                } else {
                    $adres_pogruzke_show_temp = Gruzootpravitel::where('id', '=', $one_adres['adres_pogruzki'])->get();
                    $one_adres['adres_pogruzke_show'] = $adres_pogruzke_show_temp[0]['nazvanie'];
                }
            }
            //добавляем название к адресу выгрузки
            foreach ($ts['adres_vygr_TS'] as $one_adres) {
                if ($one_adres['adres_pogruzki'] == null) {
                    $one_adres['adres_vygruzki_show'] = '';
                } else {
                    $adres_pogruzke_show_temp = Gruzootpravitel::where('id', '=', $one_adres['adres_pogruzki'])->get();
                    $one_adres['adres_vygruzki_show'] = $adres_pogruzke_show_temp[0]['nazvanie'];
                }
            }


        }
        return response()->json([
            'status' => 'success',
            'message' =>'Заявка успешно получена',
            'data' =>$orders_list,
            'TS_list' =>$TS_list,
            'adres_pogruzke_show' =>$adres_pogruzke_show,
            'adres_vygruski_show' =>$adres_vygruski_show,
        ], 200);
    }
    //если новая заявка
    public function start_new_order(Request $request)
    {
        $data_vneseniya = $request->input('data_vneseniya');
       $orders= Orders::create([
            'data_vneseniya' => $data_vneseniya
        ]);
        return response()->json([
            'status' => 'success',
            'message' =>'Заявка успешно создана',
            'data' =>$orders,
        ], 200);
    }
    public function store_xlsx(Request $request)
    {
        Orders::where('id', '=', $request['order_id'])->update([
            'nomenklatura' =>$request['file_name'],
        ]);
       $request['file_xlsx']->move(public_path('/images/orders_xlsx/'), $request['order_id'].'__'.$request['full_name']);
        return response()->json([
            'status' => 'success',
            'message' =>'Файл xlsx успешно сохранён',
        ], 200);
    }
    public function store_doc(Request $request)
    {
        $request['file_xlsx']->move(public_path('/grade_doc/'), $request['order_id'].'__'.$request['full_name']);
        return response()->json([
            'status' => 'success',
            'message' =>'Файл xlsx успешно сохранён',
        ], 200);
    }
    public function store_doc_templ(Request $request)
    {
        $doc_type= $request->input('doc_type');
        $full_name= $request->input('full_name');

            $isset = DocsTemplate::where('doc_type', $doc_type)->first();

            if ($isset !== null) {
                $path_to_del = public_path() . "/templates/" . $isset['doc_name'];
                try {
                unlink($path_to_del);
                }
                catch (\Throwable $e)
                {

                }
                $isset->update(
                [
                'doc_name' =>$request['full_name'],
                ]
            );
            }
            else{
                DocsTemplate::create(
                    [
                        'doc_type' =>$doc_type,
                        'doc_name' =>$request['full_name'],
                    ]
                );
            }

        $request['file_xlsx']->move(public_path('/templates/'), $full_name);
        return response()->json([
            'status' => 'success',
            'message' =>'Файл успешно сохранён',
        ], 200);
    }
    public function get_templ_names(Request $request)
    {
        $TH = DocsTemplate::where('doc_type','TH')->get();
        $DOV = DocsTemplate::where('doc_type','DOV')->get();
        $ZAI = DocsTemplate::where('doc_type','ZAI')->get();
        if ($TH->isEmpty()) {
            $TH='';
        }
        else
        {
            $TH=$TH[0]['doc_name'];
        }
        if ($DOV->isEmpty()) {
            $DOV='';
        }
        else
        {
            $DOV=$DOV[0]['doc_name'];
        }
        if ($ZAI->isEmpty()) {
            $ZAI='';
        }
        else
        {
            $ZAI=$ZAI[0]['doc_name'];
        }


        return response()->json([
            'status' => 'success',
            'TH' =>$TH,
            'DOV' =>$DOV,
            'ZAI' =>$ZAI,
        ], 200);
    }
    public function get_xlsx_file($filename)
    {
        return response()->download(public_path('images/orders_xlsx/'.$filename));
    }
    public function get_finall_doc_pdf_file($filename)
    {
        return response()->download(public_path('templates/'.$filename));

    }
    public function download_xlsx_orders(Request $request)
    {
        $id = $request->input('id');
        $order = Orders::where('id', '=', $id) ->get();
        $file=$id.'__'.$order[0]['nomenklatura'];
        return response()->json([
            'status' => 'success',
            'file' =>$file,
        ], 200);

    }
    public function get_finall_doc_pdf(Request $request)
    {
        ini_set('max_execution_time', 180);

//            return response()->download(public_path('templates/отчёт.txt'))

        $id = $request->input('id');
        $doc_type = $request->input('doc_type');
        $orders_list = Orders::where('id', '=', $id) ->get();
        $grade_list = FinalGrade::where('grade_id', '=', $id) ->get();

        if($doc_type=='1')
        {
            $TH = DocsTemplate::where('doc_type','TH')->get();
            $TH=$TH[0]['doc_name'];
            $template = new Template();
            $template->open(public_path('templates/'.$TH))
                ->replace('data_pogruzki', $orders_list[0]['data_pogruzki'])
                ->replace('por_nomer', $orders_list[0]['id'])
                ->replace('adres_pogruzki', $orders_list[0]['adres_pogruzke'])
                ->replace('adres_vygruzki', $orders_list[0]['adres_vygruski'])
                ->replace('kol_gruzomest', $orders_list[0]['gruzomesta_big'])
                ->replace('data_dostavki', $orders_list[0]['data_dostavki'])
                ->replace('gruzootpravitel', 'Грузоотправитель')
                ->replace('mesto_pogruzki', $orders_list[0]['adres_pogruzke'])
                ->replace('voditel', $grade_list[0]['voditel'])
                ->save(public_path('templates/fin_TH.xlsx'));

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $phpWord = $reader->load(public_path('templates/fin_TH.xlsx'));
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($phpWord);
            $writer->writeAllSheets();
            $writer->save(public_path('templates/TH.pdf'));

            return response()->json([
                'status' => 'success',
                'file' =>'TH.pdf',
            ], 200);
        }
        if($doc_type=='2')
        {
            $TH = DocsTemplate::where('doc_type','DOV')->get();
            $TH=$TH[0]['doc_name'];
            $template = new Template();
            $template->open(public_path('templates/'.$TH))
//                ->replace('data_pogruzki', $orders_list[0]['data_pogruzki'])
                ->save(public_path('templates/fin_DOV.xlsx'));

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $phpWord = $reader->load(public_path('templates/fin_DOV.xlsx'));
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($phpWord);
            $writer->save(public_path('templates/DOV.pdf'));

            return response()->json([
                'status' => 'success',
                'file' =>'DOV.pdf',
            ], 200);
        }
        if($doc_type=='3')
        {
            $TH = DocsTemplate::where('doc_type','ZAI')->get();
            $TH=$TH[0]['doc_name'];

            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('templates/'.$TH));
            $templateProcessor->
            setValue(array('{{company}}'), array('Developer'));
            $templateProcessor->saveAs(public_path('templates/fin_ZAI.docx'));

            $domPdfPath = base_path('vendor/mpdf/mpdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('MPDF');
            $wordPdf = \PhpOffice\PhpWord\IOFactory::load(public_path('templates/fin_ZAI.docx'));
            $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordPdf , 'PDF');
            $pdfWriter->save(public_path('templates/ZAI.pdf'));


            return response()->json([
                'status' => 'success',
                'file' =>'ZAI.pdf',
            ], 200);
        }
    }
    public function set_doc(Request $request)
    {
        $phpWord = new PhpWord();
//        $phpWord = \PhpOffice\PhpWord\IOFactory::load(public_path('grade_doc/2.txt'));


        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('grade_doc/Документ для редактирования.docx'));
        $templateProcessor->setValue(array('{{company}}'), array('Developer'));
        $templateProcessor->saveAs(public_path('grade_doc/3.docx'));

        $template = new Template();
        $template->open(public_path('grade_doc/Книга1.xlsx'))
        ->replace('one', 'two')
        ->save(public_path('grade_doc/test.xlsx'));

//        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('grade_doc/Книга1.xlsx'));
//        $templateProcessor->setValue(array('{{company}}'), array('Developer'));
//        $templateProcessor->saveAs(public_path('grade_doc/3.xlsx'));

//        $section = $phpWord->addSection();
//        $section->addText(
//            '"Learn from yesterday, live for today, hope for tomorrow. '
//            . 'The important thing is not to stop questioning." '
//            . '(Albert Einstein)'
//        );
//        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//        $objWriter->save(public_path('grade_doc/2.txt'));

    }
    public function header_counter_orders()
    {

        //из назначения ставки обязательно делать NULL, а не ноль
        $user = User::find(Auth::id());
        $role=$user->roles[0]['id'];
        $ocenka_unread_count=0;
        $naznachenie_stavki_unread_count=0;
        $v_rabote=0;
        $v_rabote_unread_count=0;
        if($role==1)
        {
            //общее количество заявок
            $zurnal_zaiavok = Orders::all()
                ->count();
            //количество заявок отданных какому-либо логисту
            $ocenka = Orders::
                  where('logist', '<>', 0)
                ->where('logist', '<>', null)
                ->where('naznachenie_stavki', '=', null)
                ->count();

            $naznachenie_stavki = Orders::where('naznachenie_stavki', '=', 1)->count();
            $naznachenie_stavki_unread_count=UnreadHeader::where('column_name','naznachenie_stavki')->count();
            $v_rabote = Orders::where('naznachenie_stavki', '=', null)->where('v_rabote', '=', 1)->count();

        }
        //если роль логист
        if($role==2)
        {
            //общее количество заявок
            $zurnal_zaiavok = Orders::all()
                ->count();
            //количество заявок данного логиста
            $ocenka = Orders::
                where('logist',Auth::id())
                ->where('naznachenie_stavki', '=', null)
                ->count();
            //количество не прочитанных заявок оценка

            $ocenka_unread_count=UnreadHeader::where('logist_id',$user['id'])->where('column_name','ocenka')->count();
            $naznachenie_stavki=0;
            $v_rabote = Orders::where('naznachenie_stavki', '=', null)->where('v_rabote', '=', 1)->where('logist',Auth::id())->count();
            $v_rabote_unread_count=UnreadHeader::where('column_name','v_rabote')->where('logist_id',Auth::id())->count();

        }
        //если роль менеджер
        if($role==3)
        {
            //общее количество заявок
            $zurnal_zaiavok = Orders::all()
                ->count();
            //количество заявок данного логиста
            $ocenka = 0;
            //количество не прочитанных заявок оценка
            $ocenka_unread_count=0;
            $naznachenie_stavki=0;
        }
        return response()->json([
            'status' => 'success',
            'zurnal_zaiavok' => $zurnal_zaiavok,
            'ocenka' => $ocenka,
            'ocenka_unread_count' => $ocenka_unread_count,
            'naznachenie_stavki' => $naznachenie_stavki,
            'naznachenie_stavki_unread_count' => $naznachenie_stavki_unread_count,
            'v_rabote' => $v_rabote,
            'v_rabote_unread_count' => $v_rabote_unread_count,
//            'kontrol' => $kontrol,
//            'zavershen' => $zavershen

        ], 201);

    }
    public function get_orders_list_new(Request $request)
    {
        $offset =  $request->input('offset');
        $limit =  $request->input('limit');

        //выборка по фильтру из шапки
        //0 - журнал заявок
        //1 - оценка
        //2 - назначение ставки
        //3 - в работе
        //4 - контроль
        //5 - завершён
        $order_by =  $request->input('order_by');
        $user = User::find(Auth::id());
        $role=$user->roles[0]['id'];


        //разбираем важные
        //получаем количество всех важных записей
        $all_imp = Orders::where('important', 1)
            //если сортировка по колонке оценка и юзер админ, отдаём оценки минус назначение ставки
            ->when($order_by==1&&$role==1, function($q){
                return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер логист, отдаём только его заявки минус назначение ставки
            ->when($order_by==1&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
            ->when($order_by==1&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
            ->when($order_by==2&&$role==1, function($q){
                return $q->where('naznachenie_stavki', '=', 1);
            })
            //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
            ->when($order_by==2&&$role==2, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
            ->when($order_by==2&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке в работе и юзер админ, отдаём все в работе
            ->when($order_by==3&&$role==1, function($q){
                return $q->where('v_rabote', '=', 1);
            })
            //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
            ->when($order_by==3&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
            ->when($order_by==3&&$role==3, function($q){
                return $q->where('id', 0);
            })
            ->count();

        //получаем все важные сначала
        $list_colored_imp = Orders::where('important', 1)
            //если сортировка по колонке оценка и юзер админ, отдаём оценки минус назначение ставки
            ->when($order_by==1&&$role==1, function($q){
                return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер логист, отдаём только его заявки минус назначение ставки
            ->when($order_by==1&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
            ->when($order_by==1&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
            ->when($order_by==2&&$role==1, function($q){
                return $q->where('naznachenie_stavki', '=', 1);
            })
            //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
            ->when($order_by==2&&$role==2, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
            ->when($order_by==2&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке в работе и юзер админ, отдаём все в работе
            ->when($order_by==3&&$role==1, function($q){
                return $q->where('v_rabote', '=', 1);
            })
            //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
            ->when($order_by==3&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
            ->when($order_by==3&&$role==3, function($q){
                return $q->where('id', 0);
            })
            ->get();
        $not_empty_flag=false;
//        if($all_imp>$offset&&($limit+$offset)<=$all_imp)
        if($all_imp>$offset)
        {
            $dif=$limit+$offset-$all_imp;

            if($dif>0)
            {
                if($all_imp-$offset>0)
                {
                    $temp_offset=0;
                }
                //получаем все не важные записи с листа
                $list_colored = Orders::where('id', '>=', 0)
                    ->where('important',null)
                    //если сортировка по колонке оценка и юзер админ
                    ->when($order_by==1&&$role==1, function($q){
                        return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
                    })
                    //если сортировка по колонке оценка и юзер логист, отдаём только его заявки
                    ->when($order_by==1&&$role==2, function($q){
                        return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
                    })
                    //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
                    ->when($order_by==1&&$role==3, function($q){
                        return $q->where('id', 0);
                    })
                    //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
                    ->when($order_by==2&&$role==1, function($q){
                        return $q->where('naznachenie_stavki', '=', 1);
                    })
                    //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
                    ->when($order_by==2&&$role==2, function($q){
                        return $q->where('id', 0);
                    })
                    //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
                    ->when($order_by==2&&$role==3, function($q){
                        return $q->where('id', 0);
                    })
                    //если сортировка по колонке в работе и юзер админ, отдаём все в работе
                    ->when($order_by==3&&$role==1, function($q){
                        return $q->where('v_rabote', '=', 1);
                    })
                    //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
                    ->when($order_by==3&&$role==2, function($q){
                        return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
                    })
                    //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
                    ->when($order_by==3&&$role==3, function($q){
                        return $q->where('id', 0);
                    })
                    ->offset($temp_offset)
                    ->limit($dif)
                    ->get();
                $not_empty_flag=true;
            }
        }
      else
      {
          $list_colored = Orders::where('id', '>=', 0)
              ->where('important',null)
              //если сортировка по колонке оценка и юзер админ
              ->when($order_by==1&&$role==1, function($q){
                  return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
              })
              //если сортировка по колонке оценка и юзер логист, отдаём только его заявки
              ->when($order_by==1&&$role==2, function($q){
                  return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
              })
              //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
              ->when($order_by==1&&$role==3, function($q){
                  return $q->where('id', 0);
              })
              //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
              ->when($order_by==2&&$role==1, function($q){
                  return $q->where('naznachenie_stavki', '=', 1);
              })
              //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
              ->when($order_by==2&&$role==2, function($q){
                  return $q->where('id', 0);
              })
              //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
              ->when($order_by==2&&$role==3, function($q){
                  return $q->where('id', 0);
              })
              //если сортировка по колонке в работе и юзер админ, отдаём все в работе
              ->when($order_by==3&&$role==1, function($q){
                  return $q->where('v_rabote', '=', 1);
              })
              //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
              ->when($order_by==3&&$role==2, function($q){
                  return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
              })
              //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
              ->when($order_by==3&&$role==3, function($q){
                  return $q->where('id', 0);
              })
              ->offset($offset-$all_imp)
              ->limit($limit)
              ->get();
          $not_empty_flag=true;
      }

        $res_arr=[];
        if($all_imp>$offset)
        {
        //добавляем важные записи в результирующий массив
            foreach ($list_colored_imp as $imp)
            {
                array_push($res_arr,$imp);
            }
            $res_arr = array_splice($res_arr, $offset, $limit);
        }
        if($not_empty_flag)
        {
            //добавляем все остальные записи в результирующий массив
            foreach ($list_colored as $colored)
            {
                array_push($res_arr,$colored);
            }
        }

        $count = Orders::where('id', '>=', 0)
            //если сортировка по колонке оценка и юзер админ
            ->when($order_by==1&&$role==1, function($q){
                return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер логист, отдаём только его заявки
            ->when($order_by==1&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
            ->when($order_by==1&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
            ->when($order_by==2&&$role==1, function($q){
                return $q->where('naznachenie_stavki', '=', 1);
            })
            //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
            ->when($order_by==2&&$role==2, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
            ->when($order_by==2&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке в работе и юзер админ, отдаём все в работе
            ->when($order_by==3&&$role==1, function($q){
                return $q->where('v_rabote', '=', 1);
            })
            //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
            ->when($order_by==3&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
            ->when($order_by==3&&$role==3, function($q){
                return $q->where('id', 0);
            })
            ->count();
        //получаем имя грузоотправителя
        $gruzootpravitel = Gruzootpravitel::all();

        //конец выборки по колонки 0 - журнал заявок

        return response()->json([
            'status' => 'success',
            'list_colored' => $res_arr,
            'tipes_count' => $count,
            'order_by' => $order_by,
            'role' => $role,
//            'res'=>$list_colored_imp_old,
//            'res1'=>$old_imp['id']
        ], 201);
    }

    public function get_orders_list(Request $request)
    {
        $offset =  $request->input('offset');
        $limit =  $request->input('limit');
        //получаем все важные сначала
        $list_colored_imp = Orders::where('important', 1)
            ->offset($offset)
            ->limit($limit)
            ->get();
        //получаем количество важных в том листе где будем отрисовывать результат
        $list_important_count_in_page = Orders::where('id', '>=', 0)
            ->offset($offset)
            ->limit($limit)
            ->get();
        $current_count=0;
        foreach ($list_important_count_in_page as $colored)
        {
            if($colored['important']==1)
            {
                $current_count++;
            }
        }
        //получаем количество важных записей на предыдущих листах
//        $old_imp = Orders::where('id', '>=', 0)
//            ->offset($offset)
//            ->first();
//      $list_colored_imp_old = Orders::where('important', 1)
//            ->where('id','<',$old_imp['id'])
//            ->count();
//
//        //получаем количество всех важных записей
//        $all_imp = Orders::where('important', 1)->count();
//        //проверяем а был ли сдвиг на предыдущем листе
//        $sdvig=$offset-$limit-$all_imp;
//        if($sdvig>=0)
//        {
//            $list_colored_imp_old=0;
//        }
        //получаем все записи с листа
        $list_colored = Orders::where('id', '>=', 0)
            ->offset($offset)
            //плюс количество своих записей
            ->limit($limit+$current_count)
            ->get();
        $res_arr=[];
        //добавляем важные записи в результирующий массив
        foreach ($list_colored_imp as $imp)
        {
            array_push($res_arr,$imp);
        }
        //добавляем все остальные записи в результирующий массив кроме важных
        foreach ($list_colored as $colored)
        {
            if($colored['important']!=1)
            {
                array_push($res_arr,$colored);
            }
        }
        //вырезаем все лишние записи
        $res_arr = array_splice($res_arr, 0, $limit);
        $count = Orders::where('id', '>=', 0)
            ->count();
        return response()->json([
            'status' => 'success',
            'list_colored' => $res_arr,
            'tipes_count' => $count
//            'res'=>$list_colored_imp_old,
//            'res1'=>$old_imp['id']
        ], 201);
    }
    public function delete_orders(Request $request)
    {
        $orders_id =  $request->input('orders_id');

        Orders::whereIn('id', $orders_id)->delete();

        foreach ($orders_id as $ord_id)
        {
            $logist_to_del=UnreadHeader::where('order_id',$ord_id)->where('column_name','ocenka')->get();
            UnreadHeader::where('order_id','=',$ord_id)->delete();
            if (!$logist_to_del->isEmpty()) {
                $orders_count_new=UnreadHeader::where('logist_id',$logist_to_del[0]['logist_id'])->where('column_name','ocenka')->count();
                broadcast(new UpdateLogistEvent(0,0,$orders_count_new,$logist_to_del[0]['logist_id']))->toOthers();
            }
        }
        broadcast(new DeleteOrderEvent($orders_id))->toOthers();
        return response()->json([
            'status' => 'success',
            'message' => 'заявки успешно удалены',
        ], 201);
    }
    public function important_mark(Request $request)
    {

        $orders_id =  $request->input('orders_id');
        $model =  $request->input('model');
        $model = 'App\Models\\' . $model;
        foreach ($orders_id as $order)
        {
            $single_order= $model::where('id', $order)->get();
            if($single_order[0]['important']==1)
            {
                $model::where('id', $order)->update([
                    'important' =>null,
                ]);
            }
            else
            {
                $model::where('id', $order)->update([
                    'important' =>1,
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'заявки успешно обновлены',
        ], 201);
    }
    public function mark_as_important(Request $request)
    {

        $orders_id =  $request->input('orders_id');
        foreach ($orders_id as $order)
        {
            $single_order= Orders::where('id', $order)->get();
            if($single_order[0]['important']==1)
            {
                Orders::where('id', $order)->update([
                    'important' =>null,
                ]);
            }
            else
            {
                Orders::where('id', $order)->update([
                    'important' =>1,
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'заявки успешно обновлены',
        ], 201);
    }
    public function up_gruzoot_from_select(Request $request)
    {
        $order_id =  $request->input('order_id');
        $adres_pogruzke =  $request->input('adres_id');
        $column_name =  $request->input('column_name');
        Orders::where('id', $order_id)->update([
            $column_name =>$adres_pogruzke,
        ]);
    }
}
