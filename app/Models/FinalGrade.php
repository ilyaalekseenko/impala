<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberToWords\NumberToWords;

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
    public function getStavkiList($offset = 0, $limit = 20)
    {
        return FinalGrade::query()
            ->offset((int)$offset)
            ->limit((int)$limit)
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
    public function checkIfExistTipTS($perevozchikId,$vid_TS)
    {
        $field=FinalGrade::where('perevozchik',$perevozchikId)->where('vid_TS',$vid_TS)->first();
        if ($field === null) {
            return false;
        }
        else
        {
            return true;
        }
    }
    public function getIssetVidTs($vidTsId,$perevozchikId)
    {
        return FinalGrade::where('vid_TS',$vidTsId)->where('perevozchik',$perevozchikId)->get();
    }
    public function getIssetOtkuda($vidTsId,$otkudaArr)
    {
        return FinalGrade::where('vid_TS',$vidTsId)->whereIn('grade_id',$otkudaArr)->get();
    }
    public function getVidTsPluck($grade_idArr)
    {
        return FinalGrade::whereIn('grade_id',$grade_idArr)->pluck('vid_TS');
    }
    public function getVidTsByGradeAndTs($onegrade)
    {
        return FinalGrade::where('grade_id',$onegrade['grade_id'])->where('id_ts',$onegrade['id_ts'])->pluck('vid_TS');
    }
    public function checkIfExistPer($perevozchik,$otkuda)
    {
        $flag=false;
        foreach ($otkuda as $oneOtkuda)
        {
            $field=FinalGrade::where('perevozchik',$perevozchik)->where('grade_id',$oneOtkuda['grade_id'])->where('id_ts',$oneOtkuda['id_ts'])->first();
            if ($field != null) {
                $flag=true;
            }
        }
        return $flag;
    }
    public function checkIfExistPerOne($id,$otkuda,$searchColumn)
    {

        $flag=false;

            $field=FinalGrade::where($searchColumn,$id)->where('grade_id',$otkuda['grade_id'])->where('id_ts',$otkuda['id_ts'])->first();
            if ($field != null) {
                $flag=true;
            }
        return $flag;
    }
    public function checkIfExistPerOtkudaKuda($id_ts,$eqModel,$perevozchik,)
    {
        $flag=false;

            $field=FinalGrade::where($eqModel,$perevozchik)->where('id_ts',$id_ts)->first();
            if ($field != null) {
                $flag=true;
            }

        return $flag;
    }
    public function delFinalGradeByID($grade_id)
    {
        FinalGrade:: whereIn('grade_id',$grade_id)
            ->delete();
    }
    public function getKolMest($orderId,$tsId)
    {
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ru');
        $kolMest=FinalGrade::where('grade_id',$orderId)->where('id_ts',$tsId)->pluck('kol_gruz_TS');
        $kolMest=($kolMest[0]);
        if($kolMest==null)
        {
            return '';
        }
        else
        {
           return ($kolMest.' ('.$numberTransformer->toWords($kolMest).')');
        }
    }
}
