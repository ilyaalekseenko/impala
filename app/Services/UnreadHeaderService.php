<?php

namespace App\Services;

use App\Events\UpdateLogistEvent;
use App\Models\GruzootpravitelContact;
use App\Models\UnreadHeader;

class UnreadHeaderService
{
    protected $unreadHeader;

    public function __construct(UnreadHeader $unreadHeader)
    {
        $this->unreadHeader=$unreadHeader;
    }

    public function delUnreadHeaders($orders_id)
    {
        foreach ($orders_id as $ord_id)
        {
            $logist_to_del=$this->unreadHeader->getUnreadHeaderByColumnInModel($ord_id,'ocenka');
            //удаляем из БД
            $this->unreadHeader->delUnreadHeaderInModel($ord_id);
            if (!$logist_to_del->isEmpty()) {
                //пересчитываем шапку по колонке оценка
                $orders_count_new= $this->unreadHeader->countUnreadHeaderByColumnInModel($logist_to_del[0]['logist_id'],'ocenka');
                broadcast(new UpdateLogistEvent(0,0,$orders_count_new,$logist_to_del[0]['logist_id']))->toOthers();
            }
        }
    }

}
