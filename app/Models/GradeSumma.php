<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSumma extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function delSumma($grade_id,$id_ts)
    {
        GradeSumma:: where('grade_id',$grade_id)
            ->where('id_ts',$id_ts)
            ->delete();
    }
    public function delById()
    {
        GradeSumma:: where('id',request('id'))
            ->delete();
    }

}
