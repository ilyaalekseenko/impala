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
    public function saveContact($kontakt,$gruzootpravitelId)
    {
        GruzootpravitelContact::create([
            'gruzootpravitel_id' => $gruzootpravitelId,
            'dolznost' => $kontakt['dolznost'],
            'FIO' => $kontakt['FIO'],
            'telefon' => $kontakt['telefon'],
            'email' => $kontakt['email'],
        ]);
    }

}
