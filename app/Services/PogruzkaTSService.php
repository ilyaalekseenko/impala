<?php

namespace App\Services;

use App\Models\Gruzootpravitel;
use App\Models\PogruzkaTS;
use App\Models\TS;

class PogruzkaTSService
{
    protected $pogruzkaTS;
    protected $gruzootpravitel;

    public function __construct(PogruzkaTS $pogruzkaTS, Gruzootpravitel $gruzootpravitel)
    {
        $this->pogruzkaTS=$pogruzkaTS;
        $this->gruzootpravitel=$gruzootpravitel;
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

}
