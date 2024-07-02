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
        'terminal_TS',
        'stavka_TS_bez_NDS',
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

    public function perevozchiki()
    {
        return $this->hasMany(OrdersPerevozchiki::class, 'TS_id', 'id');
    }

    public function getTsListByOrderIdInModel($orderId)
    {
//       return TS::where('order_id', $orderId)
//                       ->with([
//                'perevozchiki',
//                'perevozchiki.perevozka',
//                'perevozchiki.contacts'
//                ])
//           ->get();

        return TS::where('order_id', $orderId)
            ->with([
                'perevozchiki' => function ($query) {
                    $query->with(['perevozka' => function ($query) {
                        $query->withDefault([
                            'id' => null,
                            'perevozka_name' => null,
                            'forma_id' => null,
                            'nazvanie' => null,
                            'data_registracii' => null,
                            'telefon' => null,
                            'INN' => null,
                            'OGRN' => null,
                            'email' => null,
                            'generalnii_direktor' => null,
                            'telefon_gen_dir' => null,
                            'email_gen_dir' => null,
                            'YR_adres' => null,
                            'pochtovyi_adres' => null,
                            'gorod_bazirovania' => null,
                            'kod_ATI' => null,
                            'kommentariy' => null,
                        ]);
                    }]);
                    $query->with('contacts');
                },
            ])
            ->get();

    }
    public function setNullVidTS($vidTSId)
    {
        TS::where('vid_TS', $vidTSId)->update([
            'vid_TS' =>null,
        ]);
    }
    public function getStavkaKM($orderId,$idTS)
    {
        $ord = TS::query()->where('order_id', $orderId)->where('id_ts', $idTS)->first();

        //dd($ord);

        if($ord && $ord->stavka_TS && $ord->rasstojanie_TS)
        {
            return round($ord->stavka_TS/$ord->rasstojanie_TS,2);
        }
        else
        {
            return 0;
        }

    }
}
