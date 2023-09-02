<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnreadHeader extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getUnreadHeaderByColumnInModel($ord_id,$column_name)
    {
       return UnreadHeader::where('order_id',$ord_id)->where('column_name',$column_name)->get();
    }
    public function getUnreadHeaderByColumnWhereLogistInModel($id,$logistID,$columnName)
    {
        return UnreadHeader::where('order_id',$id)->where('logist_id',$logistID)->where('column_name',$columnName)->get();;
    }
    public function delUnreadHeaderInModel($ord_id)
    {
        UnreadHeader::where('order_id',$ord_id)->delete();
    }
    public function delUnreadHeaderLogistInModel($orderId, $logistId)
    {
        UnreadHeader::where('order_id',$orderId)->where('logist_id',$logistId)->delete();
    }
    public function countUnreadHeaderByColumnInModel($logist_id,$column_name)
    {
        return UnreadHeader::where('logist_id',$logist_id)->where('column_name',$column_name)->count();
    }
    //устанавливаем какая колонка будет не прочитанной
    public function setUnreadToLogistModel($logist_id,$order_id,$column_name)
    {
        UnreadHeader::updateOrCreate([
            'order_id' =>$order_id],
        [
            'logist_id' =>$logist_id,
            'column_name' =>$column_name,
        ]
        );
    }

    public function getUnreadHeadersLogistModel($logistID,$logistOradmin)
    {
        $counterArr=['ocenka'=>0,'naznachenieStavki'=>0,'vRabote'=>0,'kontrol'=>0,'zavershen'=>0];

        if($logistOradmin=='logist')
        {
            $unreadOrders=UnreadHeader::where('logist_id',$logistID)->get();
            foreach ($unreadOrders as $oneHeader)
        {
            if($oneHeader['column_name']=="ocenka")
            {
                $counterArr['ocenka']++;
            }
            if($oneHeader['column_name']=="naznachenie_stavki")
            {
                $counterArr['naznachenieStavki']++;
            }
            if($oneHeader['column_name']=="v_rabote")
            {
                $counterArr['vRabote']++;
            }
            if($oneHeader['column_name']=="kontrol")
            {
                $counterArr['kontrol']++;
            }
            if($oneHeader['column_name']=="zavershen")
            {
                $counterArr['zavershen']++;
            }
        }
        }
        if($logistOradmin=='admin')
        {
            $unreadOrders=UnreadHeader::where('column_name','naznachenie_stavki')->get();

            foreach ($unreadOrders as $oneHeader)
            {
                if($oneHeader['column_name']=="naznachenie_stavki")
                {
                    $counterArr['naznachenieStavki']++;
                }

            }
        }
        return $counterArr;
    }
    public function getUnreadHeadersAdminModel($logistID)
    {
        $counterArr=['ocenka'=>0,'naznachenieStavki'=>0,'vRabote'=>0,'kontrol'=>0,'zavershen'=>0];


            $unreadOrders=UnreadHeader::all();
            foreach ($unreadOrders as $oneHeader)
            {
                if($oneHeader['column_name']=="ocenka")
                {
                    $counterArr['ocenka']++;
                }
                if($oneHeader['column_name']=="naznachenie_stavki")
                {
                    $counterArr['naznachenieStavki']++;
                }
                if($oneHeader['column_name']=="v_rabote")
                {
                    $counterArr['vRabote']++;
                }
                if($oneHeader['column_name']=="kontrol")
                {
                    $counterArr['kontrol']++;
                }
                if($oneHeader['column_name']=="zavershen")
                {
                    $counterArr['zavershen']++;
                }
            }


        return $counterArr;
    }

}
