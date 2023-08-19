<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PogruzkaTS extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pogruzka_ts';
    protected $fillable = [
        'order_id',
        'id_ts',
        'pogruzka_or_vygruzka',
        'adres_pogruzki',
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

    public function tsListPogruzkaGetInModel($id,$ts,$pog_vyg)
    {
       return PogruzkaTS::where('order_id',$id)->where('pogruzka_or_vygruzka', $pog_vyg)->where('id_ts', $ts)->get();
    }
    public function setToNullColumn($column,$value)
    {
        PogruzkaTS::where($column, $value)->update([
            $column =>null,
        ]);
    }
}
