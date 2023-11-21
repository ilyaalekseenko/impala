<?php

namespace App\Services;

use App\Models\Gruzootpravitel;
use App\Models\Orders;

class ButtonsService
{
    protected $order;
    public function __construct(Orders $order)
    {
        $this->order = $order;
    }
    public function buttonStatus($orderID,$role)
    {
        $buttons=['ocenkaShowButton'=>false,'utverzdenieShowButton'=>false,'vRaboteShowButton'=>false];
        //получаем заявку
        $order=$this->order->getOrderByIdInModel($orderID);
        $naznachenieStavki=$order[0]['naznachenie_stavki'];
        $vRabote=$order[0]['v_rabote'];
        $ocenka=$order[0]['ocenka'];


        //если админ, то показываем все кнопки
        if($role==1)
        {
            $buttons['utverzdenieShowButton']=true;
            $buttons['vRaboteShowButton']=true;
            $buttons['ocenkaShowButton']=true;
        }
        //если логист
        if($role==2)
        {
            //заявка находится в оценке у логиста, он может нажать кнопку утверждение
            if($ocenka==1)
            {
                $buttons['utverzdenieShowButton']=true;
            }
        }
        //уберём текущий статус кнопок,например если заявка в оценке то нужно убрать кнпоку в оценке
        if($naznachenieStavki==1)
        {
            $buttons['utverzdenieShowButton']=false;
        }
        if($vRabote==1)
        {
            $buttons['vRaboteShowButton']=false;
        }
        if($ocenka==1)
        {
            $buttons['ocenkaShowButton']=false;
        }
        return $buttons;

    }
}
