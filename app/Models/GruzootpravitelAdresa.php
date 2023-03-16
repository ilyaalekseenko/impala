<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruzootpravitelAdresa extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveAdres($adres,$gruzootpravitelId)
    {
        GruzootpravitelAdresa::create([
            'gruzootpravitel_id' => $gruzootpravitelId,
            'nazvanie' => $adres['nazvanie'],
            'adres' => $adres['adres'],
            'FIO' => $adres['FIO'],
            'telefon' => $adres['telefon'],
            'email' => $adres['email']
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
    public function updateAdres($adres,$gruzootpravitelId)
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
                'email' => $adres['email']
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

}
