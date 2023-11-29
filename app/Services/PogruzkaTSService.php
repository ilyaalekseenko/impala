<?php

namespace App\Services;

use App\Models\GradePogruzka;
use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelAdresa;
use App\Models\PogruzkaTS;
use App\Models\TS;

class PogruzkaTSService
{
    protected $pogruzkaTS;
    protected $gruzootpravitel;
    private $gradePogruzkaModel;
    private $gruzootpravitelAdresa;

    public function __construct(PogruzkaTS $pogruzkaTS, Gruzootpravitel $gruzootpravitel,   GradePogruzka $gradePogruzkaModel, GruzootpravitelAdresa $gruzootpravitelAdresa)
    {
        $this->pogruzkaTS=$pogruzkaTS;
        $this->gruzootpravitel=$gruzootpravitel;
        $this->gradePogruzkaModel = $gradePogruzkaModel;
        $this->gruzootpravitelAdresa = $gruzootpravitelAdresa;
    }
    public function tsListPogruzkaGet($id,$ts,$pog_vyg)
    {
        return $this->pogruzkaTS->tsListPogruzkaGetInModel($id,$ts,$pog_vyg);
    }
    public function setNameShow($one_adres)
    {
        if ($one_adres['adres_pogruzki'] == null) {
            return '';
        } else {
            $adres_pogruzke_show_temp = $this->gruzootpravitel->getGruzootpravitelByIdInModel($one_adres['adres_pogruzki']);
            return $adres_pogruzke_show_temp[0]['nazvanie'];
        }
    }
    public function pogruzkaName()
    {
        $pogrVygr=$this->gradePogruzkaModel->getPogruzka(request('order_id'),request('id_ts'));
        $pogruzkaArr=[];
        $vygruzkaArr=[];
        foreach ($pogrVygr as $onePogrVygr)
        {
            if($onePogrVygr['pogruzka_or_vygruzka']==1)
            {
                $pogruzkaArr[]=$onePogrVygr['adres_pogruzki'];
            }
            if($onePogrVygr['pogruzka_or_vygruzka']==2)
            {
                $vygruzkaArr[]=$onePogrVygr['adres_pogruzki'];
            }
        }
        $pogruzkaArr=$this->gruzootpravitelAdresa->getFullName($pogruzkaArr);
        $vygruzkaArr=$this->gruzootpravitelAdresa->getFullName($vygruzkaArr);
        $resPogr='';
        $resVygr='';
        $pogruzkaArr=$pogruzkaArr->toArray();
        $vygruzkaArr=$vygruzkaArr->toArray();
        foreach($pogruzkaArr as $key =>$onePogr)
        {
            if ($key === key(array_slice($pogruzkaArr, -1, 1, true)))
            {
                $resPogr.=$onePogr['full_name'];
            }
            else
            {
                $resPogr .= $onePogr['full_name'] . "\n";
            }

        }
        foreach($vygruzkaArr as $key =>$onePogr)
        {
            if ($key === key(array_slice($vygruzkaArr, -1, 1, true)))
            {
                $resVygr.=$onePogr['full_name'];
            }
            else
            {
                $resVygr.=$onePogr['full_name']. "\n";
            }
        }
        $res=[$resPogr,$resVygr];
        return $res;
    }
    public function pogruzkaVygruzkaDopAdresa()
    {
        $pogrVygr=$this->gradePogruzkaModel->getPogruzka(request('order_id'),request('id_ts'));
        $pogruzkaArr=[];
        $vygruzkaArr=[];
        foreach ($pogrVygr as $onePogrVygr)
        {
            if($onePogrVygr['pogruzka_or_vygruzka']==1)
            {
                $pogruzkaArr[]=$onePogrVygr['adres_pogruzki'];
            }
            if($onePogrVygr['pogruzka_or_vygruzka']==2)
            {
                $vygruzkaArr[]=$onePogrVygr['adres_pogruzki'];
            }
        }

        $pogruzkaArr=$this->gruzootpravitelAdresa->getFullGruzAdres($pogruzkaArr);
        $vygruzkaArr=$this->gruzootpravitelAdresa->getFullGruzAdres($vygruzkaArr);
        $resPogr='';
        $resVygr='';
        $pogruzkaArr=$pogruzkaArr->toArray();
        $vygruzkaArr=$vygruzkaArr->toArray();
        foreach($pogruzkaArr as $key =>$onePogr)
        {
            if ($key === key(array_slice($pogruzkaArr, -1, 1, true)))
            {
                $resPogr.=$onePogr['nazvanie'].' '.$onePogr['adres'];
            }
            else
            {
                $resPogr .= $onePogr['nazvanie'].' '.$onePogr['adres'] . "\n";
            }

        }
        foreach($vygruzkaArr as $key =>$onePogr)
        {
            if ($key === key(array_slice($vygruzkaArr, -1, 1, true)))
            {
                $resVygr.=$onePogr['nazvanie'].' '.$onePogr['adres'];
            }
            else
            {
                $resVygr.=$onePogr['nazvanie'].' '.$onePogr['adres']. "\n";
            }
        }
        $res=[$resPogr,$resVygr];
        return $res;
    }

}
