<?php

namespace App\Services;

use App\Models\GruzootpravitelContact;
use App\Models\Orders;
use App\Models\UnreadHeader;

class LogistService
{

    protected $orders;
    private $UnreadHeadersModel;


    public function __construct(
        Orders $orders,
        UnreadHeader $UnreadHeadersModel,
    )
    {
        $this->orders=$orders;
        $this->UnreadHeadersModel = $UnreadHeadersModel;

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

        //установка конкретного логиста на заявку
        public function setLogistToOrderHeader($orderId,$logistId)
        {
            $this->orders->updateOneFieldInOrderInModel($orderId,'logist',$logistId);
        }
        //уставнока конкретной колонки у заявки
        public function setOrderColumn($orderId,$field)
        {
            $this->orders->updateOneFieldInOrderInModel($orderId,$field,1);
        }
        //уберём у всех колонок значение этой заявки, поставим null
        public function setNulltoAllColumnsHeader($orderId)
        {
            $this->orders->setNulltoAllColumnsHeaderInModel($orderId);
        }
        public function setLogistToColumnOld($orderId,$logistId)
        {
            //проверяем в какой колонке была заявка
            //если ни в какой то отправим в оценку
           $order=$this->orders->getFirstOrderInModel($orderId);
           if(($order['ocenka']==null)&&($order['v_rabote']==null)&&($order['naznachenie_stavki']==null))
           {
                //установим колонку оценка в Orders
                $this->setOrderColumn($orderId,'ocenka');
                //установим колонку оценка в не прочитанных
                $this->UnreadHeadersModel->setUnreadToLogistModel($logistId,$orderId,'ocenka');
           }
           if($order['v_rabote']==1)
           {
               //установим колонку оценка в не прочитанных
               $this->UnreadHeadersModel->setUnreadToLogistModel($logistId,$orderId,'v_rabote');
           }
            if($order['naznachenie_stavki']==1)
            {
                //установим колонку оценка в не прочитанных
                $this->UnreadHeadersModel->setUnreadToLogistModel($logistId,$orderId,'naznachenie_stavki');
            }
        }
        //удалено
        public function setLogistToOrder($newLogist)
        {
            //получаем id предыдущего логиста
            $old_logist_id= $this->getOldLogistId(request('id'));
            //читал ли предыдущий логист эту заявку
            $unread_flag= $this->UnreadHeadersModel->getUnreadHeaderByColumnInModel(request('id'),'ocenka');
            //если эта заявка не была прочитана предыдущим логистом
            if (!$unread_flag->isEmpty()) {
                //удаляем не прочитанную заявку предыдущего логиста
                $this->UnreadHeadersModel->delUnreadHeaderInModel(request('id'));
            }
            //считаем сколько не прочитанных заявок у предыдущего логиста
            $old_logist_unread= $this->UnreadHeadersModel->countUnreadHeaderByColumnInModel($old_logist_id,'ocenka');


            //ставим на эту заявку нового логиста
            $this->orders->updateOneFieldInOrderInModel(request('id'),'logist',request('logist'));
            //если новый логист есть и он назначен
            if($newLogist)
            {
            //назначем её не прочитанной новому логисту
            $this->UnreadHeadersModel->setUnreadToLogistModel(request('logist'),request('id'),'ocenka');
            //пересчитываем не прочитанные заявки у нового логиста
            $new_logist_orders_count=$this->UnreadHeadersModel->countUnreadHeaderByColumnInModel(request('logist'),'ocenka');
            }
            //если нет нового логиста, тоесть убрали
            else
            {
                $new_logist_orders_count=0;
            }
            return ['new_logist_orders_count'=>$new_logist_orders_count,'old_logist_unread'=>$old_logist_unread,'old_logist_id'=>$old_logist_id];
        }
        public function setNewLogist()
        {

        }
}
