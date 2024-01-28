<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeDocuments extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getAllDoc($grade_id)
    {
        return  GradeDocuments::
        where('grade_id', $grade_id)
            ->get();
    }
    public function getDoc($grade_id,$id_ts,$id_pogruzka,$id_doc_type)
    {
     return  GradeDocuments::
            where('grade_id', $grade_id)
            ->where('id_ts', $id_ts)
            ->where('id_pogruzka', $id_pogruzka)
            ->where('id_doc_type', $id_doc_type)
            ->get();
    }
    public function getAllDocPogrVygr($grade_id,$id_ts,$id_doc_type)
    {
        return  GradeDocuments::
        where('grade_id', $grade_id)
            ->where('id_ts', $id_ts)
            ->where('id_doc_type', $id_doc_type)
            ->get();
    }
    public function delDoc($grade_id,$id_ts,$id_pogruzka,$id_doc_type)
    {
        GradeDocuments::
            where('grade_id', $grade_id)
            ->where('id_ts',  $id_ts)
            ->where('id_pogruzka', $id_pogruzka)
            ->where('id_doc_type', $id_doc_type)
            ->delete();
    }
    public function delDocById($id)
    {
        GradeDocuments::
        where('id', $id)
            ->delete();
    }
}
