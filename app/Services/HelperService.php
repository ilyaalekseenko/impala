<?php

namespace App\Services;

use App\Models\GruzootpravitelContact;

class HelperService
{
    //получение строки из массива данных и преобразование в вид
    // хххх /n
    // xxxx /n и т.д
    public function getStringFromArr($dateArr,$stringName)
    {
        $tempDate='';
        foreach ($dateArr as $onePogr)
        {
            if(($onePogr[$stringName]==null)||($onePogr[$stringName]==''))
            {
                $tempDate.="\n";
            }
            else
            {
                if($stringName=='date_ts')
                {
                    $pattern = '/(\d{2}\.\d{2}\.\d{4})/';
                    if (preg_match($pattern, $onePogr[$stringName], $matches)) {
                        $onePogr[$stringName] = $matches[1];
                    }
                }

                $tempDate.=$onePogr[$stringName]. "\n";
            }
        }
        return $tempDate;
    }
}
