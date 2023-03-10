<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
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
    public function setNulltoAllColumnsHeaderInModel($orderId)
    {
        Orders::where('id', $orderId)->update([
            'ocenka' =>null,
            'v_rabote' =>null,
            'naznachenie_stavki' =>null,
        ]);
    }
    public function getOneColumnByOrderId($orderId,$columnName)
    {
        return Orders::where('id',$orderId) ->pluck($columnName)->first();
    }
    //получим количество всех заявок в колонке ( белый цвет ) всех типов

    public function allHeadersCountModel($logistId,$logistOradmin)
    {
        $counterArr=['all'=>0,'ocenka'=>0,'naznachenieStavki'=>0,'vRabote'=>0,];
        $counterArr['all']=Orders::all()->count();
        if($logistOradmin=='logist')
        {
            $Orders=Orders::where('logist',$logistId)->get();
        }
        if($logistOradmin=='admin')
        {
            $Orders=Orders::all();
        }

        foreach ($Orders as $oneHeader)
        {
            if($oneHeader['ocenka']==1)
            {
                $counterArr['ocenka']++;
            }
            if($oneHeader['naznachenie_stavki']==1)
            {
                $counterArr['naznachenieStavki']++;
            }
            if($oneHeader['v_rabote']==1)
            {
                $counterArr['vRabote']++;
            }
        }
        return $counterArr;
    }
    public function isEmptyColumnOrder($orderId)
    {
        return Orders::where('id',$orderId)->where('ocenka',null)->where('v_rabote',null)->where('naznachenie_stavki',null)->exists();
    }
    public function getColumnOrderListAdmin($columnName,$offset,$limit)
    {
        return Orders::
            when($columnName=='zurnal_zaiavok', function($q)use ($offset,$limit){
                return $q->where('id','>',0)
                ->offset($offset)
                ->limit($limit);
            })
            ->when($columnName!=='zurnal_zaiavok', function($q)use ($columnName,$offset,$limit){
                return $q->where($columnName,1)
                    ->offset($offset)
                    ->limit($limit);
            })
            ->get();
    }
    public function getColumnOrderListLogist($columnName,$offset,$limit)
    {
        return Orders::
        when($columnName=='zurnal_zaiavok', function($q)use ($columnName,$offset,$limit){
            return $q->where('id','>',0)
                ->offset($offset)
                ->limit($limit);
        })
            ->when($columnName!=='zurnal_zaiavok', function($q)use ($columnName,$offset,$limit){
                return $q->where($columnName,1)->where('logist',Auth::id())
                    ->offset($offset)
                    ->limit($limit);
            })
            ->get();
    }
    public function countColumnOrderListAdmin($columnName)
    {
        return Orders::
        when($columnName=='zurnal_zaiavok', function($q)use ($columnName){
            return $q->where('id','>',0);
        })
            ->when($columnName!=='zurnal_zaiavok', function($q)use ($columnName){
                return $q->where($columnName,1)
                    ;
            })
            ->count();
    }
    public function countColumnOrderListLogist($columnName)
    {
        return Orders::
        when($columnName=='zurnal_zaiavok', function($q)use ($columnName){
            return $q->where('id','>',0);
        })
            ->when($columnName!=='zurnal_zaiavok', function($q)use ($columnName){
                return $q->where($columnName,1)->where('logist',Auth::id())
                    ;
            })
            ->count();
    }
}
