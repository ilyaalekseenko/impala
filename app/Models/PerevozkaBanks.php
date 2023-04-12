<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerevozkaBanks extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function saveBank($bank,$perevozka_id)
    {
        PerevozkaBanks::create([
            'perevozka_id' => $perevozka_id,
            'BIK' => $bank['BIK'],
            'raschetnyi' => $bank['raschetnyi'],
            'korschet' => $bank['korschet'],
            'kommentarii' => $bank['kommentarii'],
            'bank' => $bank['bank'],
        ]);
    }
    public function deleteBank($id)
    {
        PerevozkaBanks::where('perevozka_id',$id)->delete();
    }
    public function getBanks($id)
    {
        return PerevozkaBanks::where('perevozka_id', $id)->get();
    }
    public function updateOrCreateBanks($perevozkaId,$banks)
    {
        foreach ($banks as $bank)
        {
            PerevozkaBanks::updateOrCreate( [
                'id'   => $bank['id'],
            ],
                [
                    'perevozka_id' => $perevozkaId,
                    'BIK' => $bank['BIK'],
                    'raschetnyi' => $bank['raschetnyi'],
                    'korschet' => $bank['korschet'],
                    'kommentarii' => $bank['kommentarii'],
                    'bank' => $bank['bank'],
                ],
            );
        }
    }
}
