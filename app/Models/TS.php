<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TS extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'TS';
    protected $fillable = [
        'id_ts',
        'order_id',
        'vid_TS',
        'kol_gruz_TS',
        'kol_TS_TS',
        'rasstojanie_TS',
        'ob_ves_TS',
        'ob_ob_TS',
        'checked2',
        'stavka_TS',
        'stavka_TS_za_km',
        'stavka_kp_TS',
        'marja_TS',
        'adres_pogruzki_TS',
        'adres_vygr_TS',
        'kommentari_TS',
        'terminal_TS'
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
    public function getTsListByOrderIdInModel($orderId)
    {
       return TS::where('order_id', $orderId)->get();
    }
}
