<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perevozka extends Model
{
    use HasFactory;
    protected $guarded = false;


    public function savePerevozkaModel($forma,$nazvanie,$data_registracii,$telefon,$INN,$OGRN,$email,$generalnii_direktor,$telefon_gen_dir,$email_gen_dir,$yridicheskii_adres,$pochtovyi_adres)
    {
        return Perevozka::create([
            'forma_id' => $forma,
            'nazvanie' => $nazvanie,
            'data_registracii' => $data_registracii,
            'telefon' => $telefon,
            'INN' => $INN,
            'OGRN' => $OGRN,
            'email' => $email,
            'generalnii_direktor' => $generalnii_direktor,
            'telefon_gen_dir' => $telefon_gen_dir,
            'email_gen_dir' => $email_gen_dir,
            'YR_adres' => $yridicheskii_adres,
            'pochtovyi_adres' => $pochtovyi_adres,
        ]);
    }
    public function updatePerevozchik($perevozchikId,$forma,$nazvanie,$data_registracii,$telefon,$INN,$OGRN,$email,$generalnii_direktor,$telefon_gen_dir,$email_gen_dir,$yridicheskii_adres,$pochtovyi_adres)
    {
        Perevozka::where('id', $perevozchikId)->update([
            'forma_id' => $forma,
            'nazvanie' => $nazvanie,
            'data_registracii' => $data_registracii,
            'telefon' => $telefon,
            'INN' => $INN,
            'OGRN' => $OGRN,
            'email' => $email,
            'generalnii_direktor' => $generalnii_direktor,
            'telefon_gen_dir' => $telefon_gen_dir,
            'email_gen_dir' => $email_gen_dir,
            'YR_adres' => $yridicheskii_adres,
            'pochtovyi_adres' => $pochtovyi_adres,
        ]);
    }
    public function getPerevozchiklInOnePage($offset,$limit)
    {
        return Perevozka::
              offset($offset)
            ->limit($limit)
            ->get();
    }
    public function countPerevozka()
    {
        return Perevozka::count();
    }
    public function deletePerevozka($id)
    {
        Perevozka::where('id',$id)->delete();
    }
    public function getPerevozka($id)
    {
      return Perevozka::where('id',$id)->get();
    }
    public function getPerevozkaNazvanieInModel($nazvanie)
    {
        return Perevozka::where('nazvanie', $nazvanie)->get();
    }
    public function getPerevozkaNameBYId($id)
    {
        $vid=Perevozka::where('id', $id)->get();
        return $vid[0]['nazvanie'];
    }
}

