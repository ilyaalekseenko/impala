<?php

namespace App\Services;

use App\Models\GruzootpravitelContact;

class OplataService
{
    public function setOplata($oplata_list)
    {
        $arr_to_add=[];
        foreach ($oplata_list as $oplata)
        {
            array_push($arr_to_add, $oplata);
        }
        return $arr_to_add;
    }
}
