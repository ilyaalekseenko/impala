<?php

namespace App\Http\Controllers;

use App\Models\PogruzkaTS;
use App\Models\Terminal;
use App\Models\TS;
use App\Models\TypePer;
use App\Models\VidTS;
use Illuminate\Http\Request;

class TSController extends Controller
{
    public function get_terminal_list()
    {
        $TS_list= Terminal::all();
        return response()->json([
            'status' => 'success',
            'message' =>'Список названий ТС получен',
            'terminal' =>$TS_list,
        ], 200);
    }
    public function get_type_per_list()
    {
        $TS_list= TypePer::all();
        return response()->json([
            'status' => 'success',
            'message' =>'Список названий ТС получен',
            'ts' =>$TS_list,
        ], 200);
    }
    public function get_ts_list(Request $request)
    {
        $TS_list= VidTS::all();
        return response()->json([
            'status' => 'success',
            'message' =>'Список названий ТС получен',
            'ts' =>$TS_list,
        ], 200);
    }
    public function save_ts(Request $request)
    {
        $id_ts=$request->input('id_ts');
        $order_id=$request->input('order_id');
        $vid_TS=$request->input('vid_TS');
        $stavka_TS=$request->input('stavka_TS');
        $stavka_TS_za_km=$request->input('stavka_TS_za_km');
        $stavka_kp_TS=$request->input('stavka_kp_TS');
        $marja_TS=$request->input('marja_TS');
        $kol_gruz_TS=$request->input('kol_gruz_TS');
        $kol_TS_TS=$request->input('kol_TS_TS');
        $rasstojanie_TS=$request->input('rasstojanie_TS');
        $adres_pogruzki_TS=$request->input('adres_pogruzki_TS');
        $ob_ves_TS=$request->input('ob_ves_TS');
        $ob_ob_TS=$request->input('ob_ob_TS');
        $adres_vygr_TS=$request->input('adres_vygr_TS');
        $kommentari_TS=$request->input('kommentari_TS');
        $checked2=$request->input('checked2');
        $terminal_TS=$request->input('terminal_TS');
//        return dd($adres_pogruzki_TS);

//если создаём новое ТС
        $TS_list= TS::where('order_id', '=', $order_id)->where('id_ts', '=', $id_ts)->get();
        if($TS_list->isEmpty())
        {
            TS::create([
                'id_ts' =>$id_ts,
                'order_id' =>$order_id,
                'vid_TS' =>$vid_TS,
                'stavka_TS' =>$stavka_TS,
                'stavka_TS_za_km' =>$stavka_TS_za_km,
                'stavka_kp_TS' =>$stavka_kp_TS,
                'marja_TS' =>$marja_TS,
                'kol_gruz_TS' =>$kol_gruz_TS,
                'kol_TS_TS' =>$kol_TS_TS,
                'rasstojanie_TS' =>$rasstojanie_TS,
                'ob_ves_TS' =>$ob_ves_TS,
                'ob_ob_TS' =>$ob_ob_TS,
                'kommentari_TS' =>$kommentari_TS,
                'checked2' =>$checked2,
                'terminal_TS' =>$terminal_TS,
            ]);
            PogruzkaTS::where('id_ts' ,$id_ts,)->where('order_id',$order_id)->delete();
            foreach ($adres_pogruzki_TS as $adres)
            {
                PogruzkaTS::create([
                    'id_ts' =>$id_ts,
                    'order_id' =>$order_id,
                    //погрузка 1 выгрузка 2
                    'pogruzka_or_vygruzka' =>'1',
                    'adres_pogruzki' =>$adres['adres_pogruzki'],
                ]);
            }
            foreach ($adres_vygr_TS as $adres)
            {
                PogruzkaTS::create([
                    'id_ts' =>$id_ts,
                    'order_id' =>$order_id,
                    //погрузка 1 выгрузка 2
                    'pogruzka_or_vygruzka' =>'2',
                    'adres_pogruzki' =>$adres['adres_pogruzki'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' =>'ТС сохранено',
            ], 200);
        }
        else
        {
            TS::where('order_id', '=', $order_id)->where('id_ts', '=', $id_ts)->update([
                'vid_TS' =>$vid_TS,
                'stavka_TS' =>$stavka_TS,
                'stavka_TS_za_km' =>$stavka_TS_za_km,
                'stavka_kp_TS' =>$stavka_kp_TS,
                'marja_TS' =>$marja_TS,
                'kol_gruz_TS' =>$kol_gruz_TS,
                'kol_TS_TS' =>$kol_TS_TS,
                'rasstojanie_TS' =>$rasstojanie_TS,
                'ob_ves_TS' =>$ob_ves_TS,
                'ob_ob_TS' =>$ob_ob_TS,
                'kommentari_TS' =>$kommentari_TS,
                'checked2' =>$checked2,
                'terminal_TS' =>$terminal_TS,
            ]);
            PogruzkaTS::where('id_ts' ,$id_ts,)->where('order_id',$order_id)->delete();
            foreach ($adres_pogruzki_TS as $adres)
            {
                PogruzkaTS::create([
                    'id_ts' =>$id_ts,
                    'order_id' =>$order_id,
                    //погрузка 1 выгрузка 2
                    'pogruzka_or_vygruzka' =>'1',
                    'adres_pogruzki' =>$adres['adres_pogruzki'],
                ]);
            }
            foreach ($adres_vygr_TS as $adres)
            {
                PogruzkaTS::create([
                    'id_ts' =>$id_ts,
                    'order_id' =>$order_id,
                    //погрузка 1 выгрузка 2
                    'pogruzka_or_vygruzka' =>'2',
                    'adres_pogruzki' =>$adres['adres_pogruzki'],
                ]);
            }
        }

    }

    public function delete_TS(Request $request)
    {
        $id_ts=$request->input('id_ts');
        $order_id=$request->input('order_id');
        PogruzkaTS::where('id_ts' ,$id_ts,)->where('order_id',$order_id)->delete();
        TS::where('order_id', '=', $order_id)->where('id_ts', '=', $id_ts)->delete();
    }

}
