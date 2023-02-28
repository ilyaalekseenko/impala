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
    public function delUnreadHeaderInModel($ord_id)
    {
        UnreadHeader::where('order_id','=',$ord_id)->delete();
    }
    public function countUnreadHeaderByColumnInModel($logist_id,$column_name)
    {
        return UnreadHeader::where('logist_id',$logist_id)->where('column_name',$column_name)->count();
    }
}
