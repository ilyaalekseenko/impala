<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voditel extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveVoditelModel($FIO,$telefon,$dataRozdeniya,$seriyaPassporta,$kemVydan,$kogdaVydan,$propiska,$kompaniya)
    {
        return Voditel::create([
            'FIO' => $FIO,
            'telefon' => $telefon,
            'dataRozdeniya' => $dataRozdeniya,
            'seriyaPassporta' => $seriyaPassporta,
            'kemVydan' => $kemVydan,
            'kogdaVydan' => $kogdaVydan,
            'propiska' => $propiska,
            'kompaniya' => $kompaniya,
        ]);
    }
    public function getVoditelNameBYId($id)
    {
        $vid=Voditel::where('id', $id)->get();
        return $vid[0]['FIO'];
    }
    public function getVoditelNazvanieInModel($nazvanie)
    {
        return Voditel::where('FIO', $nazvanie)->get();
    }
    public function getVoditelList($offset, $limit)
    {
        return Voditel::
              offset($offset)
            ->limit($limit)
            ->get();
    }
    public function countVoditel()
    {
        return Voditel::count();
    }
    public function getVoditel($id)
    {
        return Voditel::where('id',$id)->get();
    }
    public function updateVoditel($voditelId,$FIO,$telefon,$dataRozdeniya,$seriyaPassporta,$kemVydan,$kogdaVydan,$propiska,$kompaniya)
    {
        Voditel::where('id', $voditelId)->update([
            'FIO' => $FIO,
            'telefon' => $telefon,
            'dataRozdeniya' => $dataRozdeniya,
            'seriyaPassporta' => $seriyaPassporta,
            'kemVydan' => $kemVydan,
            'kogdaVydan' => $kogdaVydan,
            'propiska' => $propiska,
            'kompaniya' => $kompaniya,
        ]);
    }
}
