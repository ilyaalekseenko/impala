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
        'vid_perevozki',
        'naznachenie_stavki'
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
    public function getOrderByIdInModel($id)
    {
       return Orders::where('id', '=', $id) ->get();
    }
    public function getFirstOrderInModel($id)
    {
        return Orders::where('id', $id)->first();
    }
    public function createNewOrderInModel($dataVneseniya)
    {
        return Orders::create([
            'data_vneseniya' => $dataVneseniya
        ]);
    }
    public function updateOrderMassInModel()
    {
        Orders::where('id', '=', request('id'))->update([
            'data_vneseniya' =>request('data_vneseniya'),
            'rasschitat_do' =>request('rasschitat_do'),
            'nomenklatura' =>request('nomenklatura'),
            'nomer_zayavki' =>request('nomer_zayavki'),
            'kompaniya_zakazchik' =>request('kompaniya_zakazchik'),
            'menedzer_zakazchik' =>request('menedzer_zakazchik'),
            'ISD' =>request('ISD'),
            'cena_kontrakta' =>request('cena_kontrakta'),
            'data_kontrakta' =>request('data_kontrakta'),
            'adres_pogruzke' =>request('adres_pogruzke'),
            'data_pogruzki' =>request('data_pogruzki'),
            'data_dostavki' =>request('data_dostavki'),
            'adres_vygruski' =>request('adres_vygruski'),
            'komment_1' =>request('komment_1'),
            'logist' =>request('logist'),
            'gruzomesta_big' =>request('gruzomesta_big'),
            'gruzomesta_small' =>request('gruzomesta_small'),
            'rasstojanie' =>request('rasstojanie'),
            'ob_ves' =>request('ob_ves'),
            'ob_ob' =>request('ob_ob'),
            'vid_perevozki' =>request('vid_perevozki'),
        ]);
    }
    public function updateOneFieldInOrderInModel($id,$field,$data)
    {
        Orders::where('id', $id)->update([
            $field =>$data,
        ]);
    }
    public function whereInDeleteInModel($orders_id)
    {
        Orders::whereIn('id', $orders_id)->delete();
    }
}
