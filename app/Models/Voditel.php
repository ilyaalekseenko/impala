<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voditel extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveVoditelModel($FIO,$telefon,$dataRozdeniya,$seriyaPassporta,$kemVydan,$kogdaVydan,$propiska,$kompaniya,$nomer_vod_ud)
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
            'udostoverenie' => $nomer_vod_ud,
        ]);
    }
    public function getVoditelNameBYId($id)
    {
        $vid=Voditel::where('id', $id)->get();
        return $vid[0]['FIO'];
    }
    public function getFullVoditelBYId($id)
    {
        $vid=Voditel::where('id', $id)->get();
        if($vid[0]['telefon']==null)
        {
            $telefon='';
        }
        else
        {
            $telefon=' тел.: '.$vid[0]['telefon'];
        }

        return $vid[0]['FIO'].' '.$vid[0]['udostoverenie'].$telefon;
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
    public function updateVoditel($voditelId,$FIO,$telefon,$dataRozdeniya,$seriyaPassporta,$kemVydan,$kogdaVydan,$propiska,$kompaniya,$nomer_vod_ud)
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
            'udostoverenie' => $nomer_vod_ud,
        ]);
    }
}
