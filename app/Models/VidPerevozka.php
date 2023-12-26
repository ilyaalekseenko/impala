<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidPerevozka extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getAllVidPerevozka()
    {
        return VidPerevozka::all();
    }
    public function createVidPerevozka()
    {
        $res=VidPerevozka::create(
            [
                'perevozka_name' =>'',
            ]
        );
        return $res;
    }
    public function updateVidPerevozka($perevozka)
    {
        VidPerevozka::where('id', $perevozka['id'])->update([
            'perevozka_name' =>$perevozka['perevozka_name'],
        ]);
    }
    public function deleteVidPerevozka($perevozka)
    {
        Orders::where('vid_perevozki',$perevozka['id'])
            ->update([
                'vid_perevozki' =>null,
            ]);
        VidPerevozka::where('id', $perevozka['id'])->delete();
    }

}
