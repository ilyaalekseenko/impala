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
    public function saveGruzootpravitelModel($forma,$nazvanie,$data_registracii,$INN,$OGRN,$telefon,$email,$generalnii_direktor,$telefon_gen_dir,$yridicheskii_adres,$pochtovyi_adres)
    {

        if($pochtovyi_adres=='')
        {
            $pochtovyi_adres=$yridicheskii_adres;
        }
       return Gruzootpravitel::create([
            'forma_id' => $forma,
            'nazvanie' => $nazvanie,
            'data_registracii' => $data_registracii,
            'INN' => $INN,
            'OGRN' => $OGRN,
            'telefon' => $telefon,
            'email' => $email,
            'generalnii_direktor' => $generalnii_direktor,
            'telefon_gen_dir' => $telefon_gen_dir,
            'YR_adres' => $yridicheskii_adres,
            'pochtovyi_adres' => $pochtovyi_adres,
        ]);
    }
    public function updateGruzootpravitel($gruzootpravitelId,$forma,$nazvanie,$data_registracii,$INN,$OGRN,$telefon,$email,$generalnii_direktor,$telefon_gen_dir,$yridicheskii_adres,$pochtovyi_adres)
    {
        if($pochtovyi_adres=='')
        {
            $pochtovyi_adres=$yridicheskii_adres;
        }
        Gruzootpravitel::where('id', $gruzootpravitelId)->update([
            'forma_id' => $forma,
            'nazvanie' => $nazvanie,
            'data_registracii' => $data_registracii,
            'INN' => $INN,
            'OGRN' => $OGRN,
            'telefon' => $telefon,
            'email' => $email,
            'generalnii_direktor' => $generalnii_direktor,
            'telefon_gen_dir' => $telefon_gen_dir,
            'YR_adres' => $yridicheskii_adres,
            'pochtovyi_adres' => $pochtovyi_adres,
        ]);
    }
    public function getGruzWithAdres()
    {
        return Gruzootpravitel::leftJoin('gruzootpravitel_adresas', 'gruzootpravitels.id', '=', 'gruzootpravitel_adresas.gruzootpravitel_id')
            ->get();
    }

}
