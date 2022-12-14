<?php

namespace App\Http\Controllers;

use App\Models\DocsTemplate;
use App\Models\FinalGrade;
use App\Models\OplataOrders;
use App\Models\Orders;
use App\Models\PogruzkaTS;
use App\Models\TemplateVar;
use App\Models\TS;
use App\Models\LogistName;
use Barryvdh\DomPDF\Facade\Pdf;
use Gotenberg\Gotenberg;
use Gotenberg\Stream;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
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
        return view('front.orders');
    }
    public function create_orders()
    {
        return view('front.create_orders');
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
        $oplata_arr=$request->input('oplata_arr');
        OplataOrders::where('order_id','=',$id)->delete();
        foreach ($oplata_arr as $oplata)
        {
            if($oplata['oplata']!=''||$oplata['summa']!='')
            {
                OplataOrders::create([
                    'order_id' =>$id,
                    'oplata' =>$oplata['oplata'],
                    'summa' =>$oplata['summa'],
                ]);
            }
        }

    }
    //если заявка уже существует
    public function start_get_old_order(Request $request)
    {
        $id = $request->input('id');
        $orders_list = Orders::where('id', '=', $id) ->get();
        $oplata_list= OplataOrders::where('order_id', '=', $id) ->get();
        $arr_to_add=[];
       foreach ($oplata_list as $oplata)
       {
           array_push($arr_to_add, $oplata);
       }
        $orders_list[0]['oplata']=$arr_to_add;

        $log_name= LogistName::where('id', '=', $orders_list[0]['logist'])->get('logist_name');

       if (!$log_name->isEmpty()) {
        $orders_list[0]['logist_name']=$log_name[0]['logist_name'];
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
          //  return dd($ts['id_ts']);
            $TS_list_pogruzka = PogruzkaTS::where('order_id', '=', $id)->where('pogruzka_or_vygruzka', '1')->where('id_ts', $ts['id_ts'])->get();
            $TS_list_vygruzka = PogruzkaTS::where('order_id', '=', $id)->where('pogruzka_or_vygruzka', '2')->where('id_ts', $ts['id_ts'])->get();
            $ts['adres_pogruzki_TS']=$TS_list_pogruzka;
            $ts['adres_vygr_TS']=$TS_list_vygruzka;
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Заявка успешно получена',
            'data' =>$orders_list,
            'TS_list' =>$TS_list,
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
    public function get_orders_list_new(Request $request)
    {
        $offset =  $request->input('offset');
        $limit =  $request->input('limit');
        //получаем количество всех важных записей
        $all_imp = Orders::where('important', 1)->count();
        //получаем все важные сначала
        $list_colored_imp = Orders::where('important', 1)
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
            ->count();
        return response()->json([
            'status' => 'success',
            'list_colored' => $res_arr,
            'tipes_count' => $count
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
        return response()->json([
            'status' => 'success',
            'message' => 'заявки успешно удалены',
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
}
