<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Orders extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data_vneseniya',
        'rasschitat_do',
        'nomenklatura',
        'nomer_zayavki',
        'kompaniya_zakazchik',
        'menedzer_zakazchik',
        'ISD',
        'cena_kontrakta',
        'data_kontrakta',
        'adres_pogruzke',
        'data_pogruzki',
        'data_dostavki',
        'adres_vygruski',
        'komment_1',
        'logist',
        'gruzomesta_big',
        'gruzomesta_small',
        'rasstojanie',
        'ob_ves',
        'ob_ob',
        'vid_perevozki'
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
}
