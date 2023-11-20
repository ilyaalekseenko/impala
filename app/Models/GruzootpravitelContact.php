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
        GruzootpravitelContact::where('gruzootpravitel_id', $gruzootpravitel_id)->delete();
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
    public function getContact($managerId)
    {
      return GruzootpravitelContact::where('id', $managerId)->get();
    }
    public function getManagerNazvanieInModel($nazvanie,$zakazchik_id)
    {
        return GruzootpravitelContact::where('FIO', $nazvanie)->where('gruzootpravitel_id', $zakazchik_id)->get();
    }
    public function getContactsGruzootpravitel($gruzootpravitel_id)
    {
        return GruzootpravitelContact::where('gruzootpravitel_id', $gruzootpravitel_id)->get();
    }
    public function deleteContact($contactID)
    {
        GruzootpravitelContact::where('id', $contactID)->delete();
    }

    public function updateOrCreateContact($contact)
    {
        GruzootpravitelContact::updateOrCreate(
            ['id' => $contact['id']],
            [
            'dolznost' => $contact['dolznost'],
            'FIO' => $contact['FIO'],
            'telefon' => $contact['telefon'],
            'email' => $contact['email'],
        ]);
    }
}
