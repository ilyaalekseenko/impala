<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TSModal extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveTSModalModel($tip,$marka,$nomer,$tonn,$nomer_documenta,$kompaniya)
    {
        return TSModal::create([
                        'tip'=>$tip,
                        'marka'=>$marka,
                        'nomer'=>$nomer,
                        'tonn'=>$tonn,
                        'nomer_documenta'=>$nomer_documenta,
                        'kompaniya'=>$kompaniya,
        ]);
    }
    public function getTSModalNazvanieInModel($nazvanie)
    {
        return TSModal::where('nomer', $nazvanie)->get();
    }
    public function getTSModalNameBYId($id)
    {
        $vid=TSModal::where('id', $id)->get();
        return $vid[0]['nomer'];
    }
    public function getTSModalList($offset, $limit)
    {
        return TSModal::
        offset($offset)
            ->limit($limit)
            ->get();
    }
    public function countTSModal()
    {
        return TSModal::count();
    }
    public function getTSModal($id)
    {
        return TSModal::where('id',$id)->get();
    }
    public function updateTSModal($voditelId,$tip,$marka,$nomer,$tonn,$nomer_documenta,$kompaniya)
    {
        TSModal::where('id', $voditelId)->update([
            'tip'=>$tip,
            'marka'=>$marka,
            'nomer'=>$nomer,
            'tonn'=>$tonn,
            'nomer_documenta'=>$nomer_documenta,
            'kompaniya'=>$kompaniya,
        ]);
    }

}
