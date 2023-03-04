<?php

namespace App\Services;

use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelContact;
use App\Models\Orders;

class OrderService
{
    protected $order;
    protected $gruzootpravitel;
    public function __construct(Orders $order, Gruzootpravitel $gruzootpravitel)
    {
        $this->order = $order;
        $this->gruzootpravitel = $gruzootpravitel;
    }

    public function getOrderById($id)
    {
      return $this->order->getOrderByIdInModel($id);
    }
    public function getfirstOrder($id)
    {
        return $this->order->getFirstOrderInModel($id);
    }
    public function newOrder($dataVneseniya)
    {
        return $this->order->createNewOrderInModel($dataVneseniya);
    }
    public function setAdressPogrVygrShow($status)
    {
        //адрес погрузки
        if($status==null)
        {
            $adres_pogruzke_show='';
        }
        else
        {
            $adres_pogruzke_show =  $this->gruzootpravitel->getGruzootpravitelByIdInModel($status);
            $adres_pogruzke_show = $adres_pogruzke_show[0]['nazvanie'];
        }
        return $adres_pogruzke_show;
    }
    public function updateOrderMass()
    {
        $this->order->updateOrderMassInModel();
    }

}
