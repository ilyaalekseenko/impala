<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruzootpravitelBank extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveBank($bank,$gruzootpravitelId)
    {
        GruzootpravitelBank::create([
            'gruzootpravitel_id' => $gruzootpravitelId,
            'BIK' => $bank['BIK'],
            'raschetnyi' => $bank['raschetnyi'],
            'korschet' => $bank['korschet'],
            'kommentarii' => $bank['kommentarii'],
            'bank' => $bank['bank'],
        ]);
    }

}
