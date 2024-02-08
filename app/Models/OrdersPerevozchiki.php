<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersPerevozchiki extends Model
{
    use HasFactory;

    protected $guarded = false;
    protected $table = 'orders_perevozchiki';

    public function perevozka()
    {
        return $this->belongsTo(Perevozka::class, 'perevozchik_id', 'id');
    }
    public function contacts()
    {
        return $this->hasMany(PerevozkaContacts::class, 'perevozka_id', 'perevozchik_id');
    }
    public function deletePerevozchikFromOrder($id)
    {
        OrdersPerevozchiki::where('id', $id) ->delete();
    }
    public function deletePerevozchikFromOrderByOrder($id)
    {
        OrdersPerevozchiki::where('orders_id', $id) ->delete();
    }
    public function updatePerevozchikId($order_id,$key,$perevozchik_id)
    {
        $orders=OrdersPerevozchiki::where('orders_id', $order_id) ->get();
        OrdersPerevozchiki:: where('id',$orders[$key]['id'])
            ->update([
                'perevozchik_id' => $perevozchik_id,
            ]);
    }
    public function addEmptyPerevozchik($id)
    {
        return OrdersPerevozchiki::create([
            'orders_id' =>$id,
        ]);
    }

    public function updatePerevozchikField($id,$fieldName,$fieldValue)
    {
        OrdersPerevozchiki:: where('id',$id)
            ->update([
                $fieldName=> $fieldValue,
            ]);
    }
}
