<?php

namespace App\Services;

use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelContact;
use App\Models\Orders;
use App\Models\OrdersPerevozchiki;
use App\Models\TS;

class OrderService
{
    protected $order;
    protected $orderPerevozchiki;
    protected $gruzootpravitel;
    public function __construct(Orders $order, Gruzootpravitel $gruzootpravitel, OrdersPerevozchiki $orderPerevozchiki)
    {
        $this->order = $order;
        $this->gruzootpravitel = $gruzootpravitel;
        $this->orderPerevozchiki = $orderPerevozchiki;
    }

    public function updatePerevozchikByKey($order_id,$key,$perevozchik_id)
    {
        $this->orderPerevozchiki->updatePerevozchikId($order_id,$key,$perevozchik_id);

    }
    public function deleteOrdersPerevozchikiByOrderId($orderId)
    {
      $TSList=TS::where('order_id',$orderId)->get();
      foreach($TSList as $OneTS)
      {
          OrdersPerevozchiki::where('TS_id',$OneTS['id'])->delete();
      }
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
    public function updateOrderLoc()
    {
        $this->order->updateOneFieldInOrderInModel( request('id'),request('field'),request('data'));
    }
    public function getOrdersListService($userStatus,$columnName,$offset,$limit)
    {
        if($userStatus=='admin')
        {
            return $this->order->getColumnOrderListAdmin($columnName,$offset,$limit);
        }
        if($userStatus=='logist')
        {
            return $this->order->getColumnOrderListLogist($columnName,$offset,$limit);
        }
    }
    public function countOrdersListService($userStatus,$columnName)
    {
        if($userStatus=='admin')
        {
            return $this->order->countColumnOrderListAdmin($columnName);
        }
        if($userStatus=='logist')
        {
            return $this->order->countColumnOrderListLogist($columnName);
        }
    }

    public function setStatustoOrder($orders)
    {
        foreach ($orders as $order) {
            if ($order['kontrol']) {
                $order['status'] = 'На контроле';
            }
            if ($order['naznachenie_stavki']) {
                $order['status'] = 'Назначение ставки';
            }
            if ($order['v_rabote']) {
                $order['status'] = 'В работе';
            }
            if ($order['ocenka']) {
                $order['status'] = 'На оценке';
            }
            if (($order['naznachenie_stavki'] == null) && ($order['v_rabote'] == null) && ($order['ocenka'] == null) && ($order['logist'] == null)) {
                $order['status'] = 'Черновик';
            }
        }
        return $orders;
    }

    public function convertDateMonth($dateString)
    {

        $months = array(
            '01'   => 'января',
            '02'  => 'февраля',
            '03'     => 'марта',
            '04'     => 'апреля',
            '05'       => 'мая',
            '06'      => 'июня',
            '07'      => 'июля',
            '08'    => 'августа',
            '09' => 'сентября',
            '10'   => 'октября',
            '11'  => 'ноября',
            '12'  => 'декабря'
        );
        $dateParts = explode('.', $dateString);

        $day = $dateParts[0];
        $month = $months[$dateParts[1]];
        $year = $dateParts[2];

        $result = $day . ' ' . $month . ' ' . $year;
        return $result;
    }


}
