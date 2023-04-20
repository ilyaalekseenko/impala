<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalGrade extends Model
{
    use HasFactory;
    protected $table = 'final_grades';
    protected $guarded = false;

    public function delGrade($grade_id,$id_ts)
    {
        FinalGrade:: where('grade_id',$grade_id)
            ->where('id_ts',$id_ts)
            ->delete();
    }

}
