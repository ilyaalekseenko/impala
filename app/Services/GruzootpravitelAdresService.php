<?php

namespace App\Services;

use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelAdresa;



class GruzootpravitelAdresService
{
    protected $gruzootpravitel;
    protected $gruzootpravitelAdresa;


    public function __construct(Gruzootpravitel $gruzootpravitel, GruzootpravitelAdresa $gruzootpravitelAdresa)
    {
        $this->gruzootpravitel = $gruzootpravitel;
        $this->gruzootpravitelAdresa = $gruzootpravitelAdresa;
    }

    public function updateAdresa($gruzootpravitelId,$adresa,$forma,$nazvanie)
    {
        //удалим из БД те адреса которые удалил пользователь
        $this->deleteNotExistAdresa($gruzootpravitelId,$adresa);
        //проапдейтим старые адреса или создадим новые если их нет
        $this->updateOldAdresa($adresa,$gruzootpravitelId,$forma,$nazvanie);
    }

    public function deleteNotExistAdresa($gruzootpravitelId,$adresa)
    {
        $adresaOld=$this->gruzootpravitelAdresa->getAdresaByGruz($gruzootpravitelId);
        foreach ($adresaOld as $adresOld)
        {
            $flag=false;
            foreach ($adresa as $adres)
            {
                if($adres['id']!==null)
                {
                    if($adres['id']==$adresOld['id'])
                    {
                        $flag=true;
                    }
                }

            }
            if(!$flag)
            {
                $this->gruzootpravitelAdresa->delAdres($adresOld['id']);
            }
        }

    }
    public function updateOldAdresa($adresa,$gruzootpravitelId,$forma,$nazvanie)
    {
        foreach ($adresa as $adres)
        {
         $this->gruzootpravitelAdresa->updateAdres($adres,$gruzootpravitelId,$forma,$nazvanie);
        }
    }
    public function createAdresForSearch()
    {
        //получим список грузоотправителей с адресами
        $gruzootpravitel=  $this->gruzootpravitel->getGruzWithAdres();
        //формируем свою коллекцию
        $gruzColect=collect([]);
        foreach ($gruzootpravitel as $gruz)
        {
            $gruzColect[]=['id'=>$gruz['id'], 'gruzootpravitel_id'=>$gruz['gruzootpravitel_id'],'nazvanie'=>$gruz['forma_id'].' '.$gruz['nazvanie'].' '.$gruz['adres']];
        }
        return response()->json([
            'status' => 'success',
            'gruzootpravitel' =>$gruzColect,
        ], 200);
    }
    public function getOneAdresForSearch($adresId)
    {
        $adres=$this->gruzootpravitelAdresa->getAdresWithGruz($adresId);
        return $adres[0]['forma_id'].' '.$adres[0]['nazvanie'].' '.$adres[0]['adres'];
    }

}
