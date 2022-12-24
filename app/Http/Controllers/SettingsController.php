<?php

namespace App\Http\Controllers;

use App\Models\FormaModal;
use App\Models\Perevozka;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update_perevozka_settings(Request $request)
    {
        $perevozka=$request->input('perevozka');
        Perevozka::where('id', '=',$perevozka['id'])->update([
            'perevozka_name' =>$perevozka['perevozka_name'],
        ]);
    }
    public function update_forma_settings(Request $request)
    {
        $forma=$request->input('forma');
        FormaModal::where('id', '=',$forma['id'])->update([
            'forma_name' =>$forma['forma_name'],
        ]);
    }
    public function get_forma_gruzootpravitel_list()
    {
        $forma = FormaModal::all();
        return response()->json([
            'status' => 'success',
            'forma_list' =>$forma,
        ], 200);
    }
    public function delete_perevozka_settings(Request $request)
    {
        $perevozka=$request->input('perevozka');
        Perevozka::where('id', '=',$perevozka['id'])->delete();
    }
    public function delete_forma_settings(Request $request)
    {
        $forma=$request->input('forma');
        FormaModal::where('id', '=',$forma['id'])->delete();
    }
    public function add_perevozka_settings(Request $request)
    {
       $res=Perevozka::create(
            [
                'perevozka_name' =>'',
            ]
        );
        return response()->json([
            'status' => 'success',
            'result' =>$res,
        ], 200);

    }
    public function add_forma_settings(Request $request)
    {
        $res=FormaModal::create(
            [
                'forma_name' =>'',
            ]
        );
        return response()->json([
            'status' => 'success',
            'result' =>$res,
        ], 200);
    }
}
