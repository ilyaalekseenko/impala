<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerevozkaContacts extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function perevozka()
    {
        return $this->belongsTo(Perevozka::class, 'perevozka_id');
    }

    public function deleteContact($id)
    {
        PerevozkaContacts::where('perevozka_id',$id)->delete();
    }
    public function deleteOneContact($id)
    {
        PerevozkaContacts::where('id',$id)->delete();
    }
    public function saveContact($kontakt,$perevozkaId)
    {
        PerevozkaContacts::create([
            'perevozka_id' => $perevozkaId,
            'dolznost' => $kontakt['dolznost'],
            'FIO' => $kontakt['FIO'],
            'telefon' => $kontakt['telefon'],
            'email' => $kontakt['email'],
        ]);
    }
    public function getContacts($id)
    {
      return PerevozkaContacts::where('perevozka_id', $id)->get();
    }
    public function updateOrCreateContacts($perevozkaId,$contacts)
    {
        foreach ($contacts as $oneContact)
        {
            PerevozkaContacts::updateOrCreate( [
                'id'   => $oneContact['id'],
            ],
                [
                    'perevozka_id' => $perevozkaId,
                    'dolznost' => $oneContact['dolznost'],
                    'FIO' => $oneContact['FIO'],
                    'telefon' => $oneContact['telefon'],
                    'email' => $oneContact['email'],
                ],
            );
        }
    }


}
