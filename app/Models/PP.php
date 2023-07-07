<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class PP extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function savePPModel($request)
    {
        $PP=PP::create($request->only('marka', 'nomer', 'dlina', 'tonn', 'nomer_documenta', 'kompaniya'));
        return $PP;
    }
    public function getPPNazvanieInModel($nazvanie)
    {
        return PP::where('nomer', $nazvanie)->get();
    }
    public function getPPNameBYId($id)
    {
        $vid=PP::where('id', $id)->get();
        return $vid[0]['nomer'];
    }
    public function getPPList($offset, $limit)
    {
        return PP::
        offset($offset)
            ->limit($limit)
            ->get();
    }
    public function countPP()
    {
        return PP::count();
    }
    public function getPP($id)
    {
        return PP::where('id',$id)->get();
    }
    public function updatePP($voditelId,$marka,$nomer,$dlina,$tonn,$nomer_documenta,$kompaniya)
    {
        PP::where('id', $voditelId)->update([
            'marka'=>$marka,
            'nomer'=>$nomer,
            'dlina'=>$dlina,
            'tonn'=>$tonn,
            'nomer_documenta'=>$nomer_documenta,
            'kompaniya'=>$kompaniya,
        ]);
    }

}
