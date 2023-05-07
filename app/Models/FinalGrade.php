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
    public function upFinalGradeToNull()
    {
        FinalGrade:: where(request('searchColumnGrade'),request('id'))
        ->update([
            request('searchColumnGrade') => NULL,
        ]);

    }
    public function upFinalGradePerevozka($id)
    {
        FinalGrade:: where('perevozchik',$id)
            ->update([
                'perevozchik' => NULL,
            ]);

    }

}
