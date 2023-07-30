<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruzootpravitelAdresa extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveAdres($adres,$gruzootpravitelId,$forma,$nazvanie)
    {
        GruzootpravitelAdresa::create([
            'gruzootpravitel_id' => $gruzootpravitelId,
            'nazvanie' => $adres['nazvanie'],
            'adres' => $adres['adres'],
            'FIO' => $adres['FIO'],
            'telefon' => $adres['telefon'],
            'email' => $adres['email'],
            'full_name' => $forma.' '.$nazvanie.' '.$adres['nazvanie'].' '.$adres['adres']
        ]);
    }
    public function delAdres($adresId)
    {
        GruzootpravitelAdresa::where('id',$adresId)->delete();

    }
    public function getAdresaByGruz($gruzootpravitelId)
    {
       return GruzootpravitelAdresa::where('gruzootpravitel_id',$gruzootpravitelId)->get();
    }
    public function updateAdres($adres,$gruzootpravitelId,$forma,$nazvanie)
    {
        GruzootpravitelAdresa::updateOrCreate(
        //массив что ищем
            [
                'id' => $adres['id'],
            ],
            //массив что апдейтим
            [
                'gruzootpravitel_id' => $gruzootpravitelId,
                'nazvanie' => $adres['nazvanie'],
                'adres' => $adres['adres'],
                'FIO' => $adres['FIO'],
                'telefon' => $adres['telefon'],
                'email' => $adres['email'],
                'full_name'=>$forma.' '.$nazvanie.' '.$adres['nazvanie'].' '.$adres['adres']
            ]);
    }
    public function getAdresByIdWithGruz($adresId)
    {
        //получить адрес вместе со столбцами грузоотправителя
       return GruzootpravitelAdresa::where('id',$adresId)->get();
//        return Gruzootpravitel::leftJoin('gruzootpravitel_adresas', 'gruzootpravitels.id', '=', 'gruzootpravitel_adresas.gruzootpravitel_id')
//            ->get();
    }
    public function getAdresWithGruz($adresId)
    {
        return GruzootpravitelAdresa::
            leftJoin('gruzootpravitels', 'gruzootpravitel_adresas.gruzootpravitel_id', '=', 'gruzootpravitels.id')
            ->select('gruzootpravitel_adresas.*', 'gruzootpravitels.nazvanie', 'gruzootpravitels.forma_id')
            ->where('gruzootpravitel_adresas.id',$adresId)
            ->get();
    }
    public function getAllAdresWithGruz()
    {
        return GruzootpravitelAdresa::
        leftJoin('gruzootpravitels', 'gruzootpravitel_adresas.gruzootpravitel_id', '=', 'gruzootpravitels.id')
            ->select('gruzootpravitel_adresas.*', 'gruzootpravitels.nazvanie', 'gruzootpravitels.forma_id')
            ->get();
    }
    public function getAdresById($adresId)
    {
        $adres= GruzootpravitelAdresa::where('id',$adresId)->get();
        if($adres->isEmpty())
        {
            return false;
        }
        return $adres[0]['adres'];
    }
    public function countAdresa()
    {
        return GruzootpravitelAdresa::count();
    }
    public function getAdresByName($fromto)
    {
        return GruzootpravitelAdresa::where('adres', 'like', '%'.$fromto.'%')->get();
    }
    public function getFullName($pogrVygrIds)
    {
        return GruzootpravitelAdresa::whereIn('id',$pogrVygrIds)->get('full_name');
    }
    public function getOneName($id)
    {
        return GruzootpravitelAdresa::where('id',$id)->get('full_name');
    }
}
