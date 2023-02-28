<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruzootpravitelContact extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function deleteContactMod($gruzootpravitel_id)
    {
        GruzootpravitelContact::where('gruzootpravitel_id', '=',$gruzootpravitel_id)->delete();
    }

}
