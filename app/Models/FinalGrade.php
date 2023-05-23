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
    public function getGradeListByIds($ids)
    {
        return FinalGrade::whereIn('id',$ids)
//              offset(request('offset'))
//            ->limit(request('limit'))
            ->get();
    }
    public function getStavkiList()
    {
            return FinalGrade::
            offset(request('offset'))
            ->limit(request('limit'))
            ->get();
    }
    public function countGrades()
    {
        return FinalGrade::count();
    }
    public function upFinalGradePerevozchik()
    {
        FinalGrade:: where('id',request('finalGradeId'))
            ->update([
                'perevozchik' => request('perevozchikId'),
            ]);
    }
    public function getGradeIdTs($id)
    {
      return FinalGrade:: where('id',$id)->get();
    }
    public function getIdByGrade($gradeId,$idTS)
    {
        return FinalGrade::where('grade_id',$gradeId)->where('id_ts',$idTS)->get();
    }
    public function getIdBYColumnId($columnName, $columnValue)
    {
        return FinalGrade:: where($columnName,$columnValue)->get();
    }
    public function checkIfExist($id,$field)
    {
        $field=FinalGrade::where($field,$id)->first();
        if ($field === null) {
            return false;
        }
        else
        {
            return true;
        }
    }

}
