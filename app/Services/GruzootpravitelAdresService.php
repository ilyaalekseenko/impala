<?php

namespace App\Services;

use App\Models\GradePogruzka;
use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelAdresa;
use App\Models\Orders;
use App\Models\PogruzkaTS;


class GruzootpravitelAdresService
{
    protected $gruzootpravitel;
    protected $gruzootpravitelAdresa;
    protected $orders;
    protected $pogruzkaTS;
    protected $gradePogruzka;


    public function __construct(Gruzootpravitel $gruzootpravitel, GruzootpravitelAdresa $gruzootpravitelAdresa, Orders $orders, PogruzkaTS $pogruzkaTS, GradePogruzka $gradePogruzka)
    {
        $this->gruzootpravitel = $gruzootpravitel;
        $this->gruzootpravitelAdresa = $gruzootpravitelAdresa;
        $this->orders = $orders;
        $this->pogruzkaTS = $pogruzkaTS;
        $this->gradePogruzka = $gradePogruzka;
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
                //удалим адрес в orders погрузка и выгрузка
                $this->orders->setToNullColumn('adres_pogruzke',$adresOld['id']);
                $this->orders->setToNullColumn('adres_vygruski',$adresOld['id']);
                $this->pogruzkaTS->setToNullColumn('adres_pogruzki',$adresOld['id']);
                $this->gradePogruzka->setToNullColumn('adres_pogruzki',$adresOld['id']);

                //удалим адреса
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
    //получить данные ГО по адресу погрузки
    public function getGObyAdres($adresId)
    {
        $data=$this->gruzootpravitelAdresa->getGruzId($adresId);
        $GOid=$data[0]['gruzootpravitel_id'];
        $GO=$this->gruzootpravitel->getGruzootpravitelByIdInModel($GOid);
        return $GO[0]['forma_id'].' '.$GO[0]['nazvanie'].', '.'ИНН'.' '.$GO[0]['INN'].' '.$GO[0]['YR_adres'].' '.$GO[0]['telefon'];
    }
    //получить данные ГО по адресу погрузки короткая версия, название и адрес
    public function getGObyAdresNazAdres($adresId)
    {
        $data=$this->gruzootpravitelAdresa->getGruzId($adresId);
        $GOid=$data[0]['gruzootpravitel_id'];
        $GO=$this->gruzootpravitel->getGruzootpravitelByIdInModel($GOid);
        return $GO[0]['forma_id'].' '.$GO[0]['nazvanie']. ' '.$GO[0]['YR_adres'];
    }


}
