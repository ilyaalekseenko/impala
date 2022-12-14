<?php

namespace App\Http\Controllers;

use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelBank;
use App\Models\GruzootpravitelContact;
use App\Models\GruzootpravitelFile;
use Illuminate\Http\Request;

class GruzootpravitelController extends Controller
{
    public function get_gruzootpravitel_modal()
    {
        $gruzootpravitel = Gruzootpravitel::where('id', '=', 1)->get();

        return response()->json([
            'status' => 'success',
            'message' =>'Грузоотправитель успешно получен',
            'data' =>$gruzootpravitel->forma_select,
        ], 200);
    }
    public function save_gruzootpravitel(Request $request)
    {
        $forma = $request->input('forma');
        $nazvanie = $request->input('nazvanie');
        $data_registracii = $request->input('data_registracii');
        $INN = $request->input('INN');
        $OGRN = $request->input('OGRN');
        $telefon = $request->input('telefon');
        $email = $request->input('email');
        $generalnii_direktor = $request->input('generalnii_direktor');
        $telefon_gen_dir = $request->input('telefon_gen_dir');
        $yridicheskii_adres = $request->input('yridicheskii_adres');
        $pochtovyi_adres = $request->input('pochtovyi_adres');
        $kontakty = $request->input('kontakty');
        $bank_arr = $request->input('bank_arr');
        $doc_files = $request->input('doc_files');
              $gruz_main= Gruzootpravitel::create([
            'forma_id' => $forma,
            'nazvanie' => $nazvanie,
            'data_registracii' => $data_registracii,
            'INN' => $INN,
            'OGRN' => $OGRN,
            'telefon' => $telefon,
            'email' => $email,
            'generalnii_direktor' => $generalnii_direktor,
            'telefon_gen_dir' => $telefon_gen_dir,
            'YR_adres' => $yridicheskii_adres,
            'pochtovyi_adres' => $pochtovyi_adres,
        ]);
        if($kontakty)
        {
           foreach ($kontakty as $kontakt)
           {
             GruzootpravitelContact::create([
            'gruzootpravitel_id' => $gruz_main['id'],
            'dolznost' => $kontakt['dolznost'],
            'FIO' => $kontakt['FIO'],
            'telefon' => $kontakt['telefon'],
            'email' => $kontakt['email'],
        ]);
           }
        }
        if($bank_arr)
        {
            foreach ($bank_arr as $bank)
            {
                GruzootpravitelBank::create([
                    'gruzootpravitel_id' => $gruz_main['id'],
                    'BIK' => $bank['BIK'],
                    'raschetnyi' => $bank['raschetnyi'],
                    'korschet' => $bank['korschet'],
                    'kommentarii' => $bank['kommentarii'],
                    'bank' => $bank['bank'],
                ]);
            }
        }
        if($doc_files)
        {
            foreach ($doc_files as $doc)
            {
                GruzootpravitelFile::create([
                    'gruzootpravitel_id' => $gruz_main['id'],
                    'file_name' => $doc['file_name'].'.'.$doc['ext'],
                    'server_name' => $doc['doc_path'],
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Успешно сохранено',
        ], 200);
    }
    public function delete_one_file_modal(Request $request)
    {
        $doc = $request->input('doc');

        try {
            $path_to_del = public_path() . "/modal/" . $doc['doc_path'];
            unlink($path_to_del);
        }
        catch (\Throwable $e)
        {

        }
    }
    public function download_modal_file($doc)
    {
        $filetopath=public_path("/modal/".$doc);
        return response()->download($filetopath);
    }
    public function delete_files_modal(Request $request)
    {
        $doc_files = $request->input('doc_files');

        if($doc_files)
        {
        foreach ($doc_files as $doc)
        {

        try {
                $path_to_del = public_path() . "/modal/" . $doc['doc_path'];
                unlink($path_to_del);
            }
            catch (\Throwable $e)
            {

            }
            }
            }
    }
    public function store_modal_file_temp(Request $request)
    {
        $file_name = $request->input('file_name');
        $extension = $request->input('extension');

        $path=time().'_'.$request['file_name'].'.'.$request['extension'];
//        $doc_in_db=GruzootpravitelFile::
//        create([
//            'gruzootpravitel_id' => $gruz_main['id'],
//            'BIK' => $bank['BIK'],
//            'raschetnyi' => $bank['raschetnyi'],
//            'korschet' => $bank['korschet'],
//            'kommentarii' => $bank['kommentarii'],
//            'bank' => $bank['bank'],
//        ]);
//        if (!$doc_in_db->isEmpty()) {
//            try {
//                $path_to_del = public_path() . "/grade_doc/" . $doc_in_db[0]['path_doc'];
//                unlink($path_to_del);
//            }
//            catch (\Throwable $e)
//            {
//
//            }
//        }
        $request['file']->move(public_path('/modal/'), $path);
        return response()->json([
            'status' => 'success',
            'message' =>'Временный файл modal успешно сохранён',
            'path' =>$path
        ], 200);
    }

}
