<?php

namespace App\Http\Controllers;

use App\Models\FormaModal;
use App\Models\Gruzootpravitel;
use App\Models\Perevozka;
use App\Models\User;
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
    public function  update_user(Request $request)
    {
        $user_id=$request->input('user_id');
        $search=$request->input('search');
        $data=$request->input('data');
        $request->validate([
            'name' => ['string', 'max:255'],
            'email' => [ 'string', 'email', 'max:255', 'unique:users'],
            'first_name' => [ 'max:255'],
            'last_name' => [ 'max:255'],
            'patronymic' => ['max:255'],
            'dolznost' => ['max:255'],
            'telefon' => ['max:255'],
            'data_rozdenia' => ['max:255'],

        ]);
        User::where('id', '=',$user_id)->update([
            $search =>$data,
        ]);
        return response()->json([
            'status' => 'success',
        ], 201);
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
        $forma = Gruzootpravitel::all();
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
