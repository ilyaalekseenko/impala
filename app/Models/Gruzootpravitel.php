<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gruzootpravitel extends Model
{
    use HasFactory;

    protected $guarded = false;

//    public function forma_select()
//    {
//        return $this->belongsTo(FormaGruzootpravitel::class, 'id');
//    }
    public function getGruzootpravitelByIdInModel($id)
    {
        return Gruzootpravitel::where('id', $id) ->get();
    }


}
