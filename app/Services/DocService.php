<?php

namespace App\Services;

use App\Models\DocsTemplate;
use App\Models\GruzootpravitelContact;

class DocService
{

    protected $docsTemplate;
    public function __construct(DocsTemplate $docsTemplate)
    {
        $this->docsTemplate = $docsTemplate;
    }

    //откуда берём ( например из запроса), куда ложим и имя с которым сохраняем
    public function storeDoc($from_req, $to, $name)
    {
        $from_req->move(public_path($to), $name);
    }
    public function delDoc($path_to_del)
    {
        try {
            unlink($path_to_del);
        }
        catch (\Throwable $e)
        {

        }
    }


}
