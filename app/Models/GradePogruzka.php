<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradePogruzka extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function delPogruzka($grade_id,$id_ts)
    {
        GradePogruzka:: where('grade_id',$grade_id)
            ->where('id_ts',$id_ts)
            ->delete();
    }
    public function deleteOnePogrVygr($grade_id,$id_ts,$id_pogruzka,$pogruzka_or_vygruzka)
    {
       $id=GradePogruzka:: where('grade_id',$grade_id)
            ->where('id_ts',$id_ts)
            ->where('id_pogruzka',$id_pogruzka)
            ->where('pogruzka_or_vygruzka',$pogruzka_or_vygruzka)
            ->get();
        GradePogruzka:: where('grade_id',$grade_id)
            ->where('id_ts',$id_ts)
            ->where('id_pogruzka',$id_pogruzka)
            ->where('pogruzka_or_vygruzka',$pogruzka_or_vygruzka)
            ->delete();
        return $id[0]['id'];
    }
    public function getRowByPogruzka($adres_pogruzke,$pogr_vygr)
    {
        return GradePogruzka::where('adres_pogruzki',$adres_pogruzke)->where('pogruzka_or_vygruzka',$pogr_vygr)->get();
    }
    public function getRowByPogruzkaPluck($adres_pogruzke,$pogr_vygr)
    {
        return GradePogruzka::where('adres_pogruzki',$adres_pogruzke)->where('pogruzka_or_vygruzka',$pogr_vygr)->pluck('grade_id');
    }
    public function getSecondPogruzka($pogr_vygr,$otkuda,$kuda)
    {
        if($pogr_vygr==1)
        {
            $pogr_vygr=2;
            $searchField=$kuda;
        }
        else
        {
            $pogr_vygr=1;
            $searchField=$otkuda;
        }
        return GradePogruzka::where('adres_pogruzki',$searchField)->where('pogruzka_or_vygruzka',$pogr_vygr)->get();
    }
    public function getPogruzka($grade_id,$id_ts)
    {
       return GradePogruzka::where('grade_id',$grade_id)
            ->where('id_ts',$id_ts)
            ->get();
    }
    public function setToNullColumn($column,$value)
    {
        GradePogruzka::where($column, $value)->update([
            $column =>null,
        ]);
    }

}
