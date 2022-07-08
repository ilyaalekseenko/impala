<?php

namespace App\Http\Controllers;

use App\Models\OplataOrders;
use App\Models\Orders;
use App\Models\TS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
       //получаем список ТС
        $TS_list= TS::where('order_id', '=', $id)->get();
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
        $request['file_xlsx']->move(public_path('/images/orders_xlsx/'), $request['order_id'].'__'.$request['file_name'].'.xlsx');
        return response()->json([
            'status' => 'success',
            'message' =>'Файл xlsx успешно сохранён',
        ], 200);
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
