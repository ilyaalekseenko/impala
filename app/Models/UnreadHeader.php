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
        UnreadHeader::where('order_id','=',$ord_id)->delete();
    }
    public function countUnreadHeaderByColumnInModel($logist_id,$column_name)
    {
        return UnreadHeader::where('logist_id',$logist_id)->where('column_name',$column_name)->count();
    }
    public function setUnreadToLogistModel($logist_id,$order_id,$column_name)
    {
        UnreadHeader::firstOrCreate([
            'logist_id' =>$logist_id,
            'column_name' =>$column_name,
            'order_id' =>$order_id]);
    }
}
