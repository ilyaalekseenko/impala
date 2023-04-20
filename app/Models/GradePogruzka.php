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

}
