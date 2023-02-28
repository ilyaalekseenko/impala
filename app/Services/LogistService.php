<?php

namespace App\Services;

use App\Models\GruzootpravitelContact;
use App\Models\Orders;

class LogistService
{

    protected $orders;

    public function __construct(Orders $orders)
    {
        $this->orders=$orders;
    }

        public function getOldLogistId($id)
        {
            $old_logist_id=$this->orders->getOrderByIdInModel($id);
            //если не было предыдущего логиста
            if ($old_logist_id[0]['logist']==null) {
                //тогда назначем предыдущему логисту id ноль
                return 0;
            }
            else
            {
                //если был предыдущий логист тогда получаем его id
                return $old_logist_id[0]['logist'];
            }
        }
}
