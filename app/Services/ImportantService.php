<?php

namespace App\Services;

use App\Models\GruzootpravitelContact;

class ImportantService
{
    public function importantMarkGlobal()
    {
        $model = 'App\Models\\' . request('model');
        foreach (request('orders_id') as $order)
        {
            $single_order= $model::where('id', $order)->get();
            if($single_order[0]['important']==1)
            {
                $model::where('id', $order)->update([
                    'important' =>null,
                ]);
            }
            else
            {
                $model::where('id', $order)->update([
                    'important' =>1,
                ]);
            }
        }
    }
}
