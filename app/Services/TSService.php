<?php

namespace App\Services;

use App\Models\GruzootpravitelContact;
use App\Models\TS;

class TSService
{
    protected $TS;

    public function __construct(TS $TS)
    {
        $this->TS=$TS;
    }

    public function getTsListByOrderId($id)
    {
        return $this->TS->getTsListByOrderIdInModel($id);
    }
}
