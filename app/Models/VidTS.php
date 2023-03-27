<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class VidTS extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'vid_TS';
    protected $fillable = [
        'ts_list_id',
        'ts_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getVidTSList($offset, $limit)
    {
      return VidTS::
            offset($offset)
            ->limit($limit)
            ->get();
    }
    public function countVidTs()
    {
        return VidTS::count();
    }
    public function deleteVidTS($vidTSId)
    {
        VidTS::where('id', $vidTSId)->delete();
    }
    public function updateVidTS($vidTSId,$ts_name)
    {
        VidTS::where('id',$vidTSId)->update([
            'ts_name' =>$ts_name,
        ]);
    }
    public function createVidTS()
    {
        return VidTS::create([]);
    }
}
