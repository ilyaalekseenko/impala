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
        'naznachenie_stavki',
        'kontrol',
        'zavershen',
        'perevozchik',
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

    public function ts()
    {
        return $this->hasMany(TS::class, 'order_id', 'id');
    }
//    public function perevozchiki()
//    {
//        return $this->hasMany(OrdersPerevozchiki::class, 'orders_id', 'id');
//    }

    public function getOrderByIdInModel($id)
    {
        return Orders::where('id', $id)
//            ->with([
//                'perevozchiki',
//                'perevozchiki.perevozka',
//                'perevozchiki.contacts'
//                ])
            ->get();
    }
    public function getFirstOrderInModel($id)
    {
        return Orders::where('id', $id)->first();
    }
    public function createNewOrderInModel($dataVneseniya)
    {
        // Получаем наибольшее значение поля nomer_zayavki
        $maxNomerZayavki = Orders::max('nomer_zayavki');
        if($maxNomerZayavki==null)
        {
            $maxNomerZayavki = Orders::max('id');
        }
        if($maxNomerZayavki==null)
        {
            $maxNomerZayavki = 0;
        }
// Увеличиваем значение на 1
        $newNomerZayavki = $maxNomerZayavki + 1;
// Создаем новую заявку с увеличенным значением поля nomer_zayavki
        $newOrder = Orders::create([
            'data_vneseniya' => $dataVneseniya,
            'nomer_zayavki' => $newNomerZayavki,
        ]);
        return $newOrder;
    }
    public function updateOrderMassInModel()
    {
        Orders::where('id', '=', request('id'))->update([
            'data_vneseniya' =>request('data_vneseniya'),
            'rasschitat_do' =>request('rasschitat_do'),
            'nomenklatura' =>request('nomenklatura'),
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
            'timeVneseniya' =>request('timeVneseniya'),
            'timeRasscheta' =>request('timeRasscheta')
        ]);
    }
    public function updateOneFieldInOrderInModel($id,$field,$data)
    {
        Orders::where('id', $id)->update([
            $field =>$data,
        ]);
    }
    public function setToNullColumn($column,$value)
    {
        Orders::where($column, $value)->update([
            $column =>null,
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
            'kontrol' =>null,
            'zavershen' =>null,
        ]);
    }
    public function getOneColumnByOrderId($orderId,$columnName)
    {
        return Orders::where('id',$orderId) ->pluck($columnName)->first();
    }
    //получим количество всех заявок в колонке ( белый цвет ) всех типов

    public function allHeadersCountModel($logistId,$logistOradmin)
    {
        $counterArr=['all'=>0,'ocenka'=>0,'naznachenieStavki'=>0,'vRabote'=>0,'kontrol'=>0,'zavershen'=>0];
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
            if($oneHeader['kontrol']==1)
            {
                $counterArr['kontrol']++;
            }
            if($oneHeader['zavershen']==1)
            {
                $counterArr['zavershen']++;
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
        when($columnName == 'zurnal_zaiavok', function ($q) use ($offset, $limit) {
            return $q->where('orders.id', '>', 0)
                ->offset($offset)
                ->limit($limit);
        })
            ->when($columnName !== 'zurnal_zaiavok', function ($q) use ($columnName, $offset, $limit) {
                return $q->where($columnName, 1)->where('orders.id', '>', 0)
                    ->offset($offset)
                    ->limit($limit);
            })
            ->leftJoin('gruzootpravitel_adresas as o1', 'orders.adres_pogruzke', '=', 'o1.id')
            ->leftJoin('gruzootpravitel_adresas as o2', 'orders.adres_vygruski', '=', 'o2.id')
            ->leftJoin('users as u', 'orders.logist', '=', 'u.id') // Добавлено соединение с таблицей users
            ->with(['ts' => function ($query) {
                $query->select('order_id', TS::raw('COALESCE(SUM(stavka_TS * kol_TS_TS), 0) as total'))
                    ->groupBy('order_id');
            }])
            ->select('orders.*', 'o1.full_name as otkuda', 'o2.full_name as kuda',  Orders::raw("CONCAT_WS(' ', NULLIF(u.first_name, ''), NULLIF(u.last_name, ''), NULLIF(u.patronymic, '')) as logistFIO"))// Объединение полей пользователя
            ->orderByRaw('CAST(nomer_zayavki AS DECIMAL) DESC')
            ->get();

    }
    public function getColumnOrderListLogist($columnName,$offset,$limit)
    {
        return Orders::
        when($columnName=='zurnal_zaiavok', function($q)use ($columnName,$offset,$limit){
            return $q->where('orders.id','>',0)->where('logist',Auth::id())
                ->offset($offset)
                ->limit($limit);
        })
            ->when($columnName!=='zurnal_zaiavok', function($q)use ($columnName,$offset,$limit){
                return $q->where($columnName,1)->where('logist',Auth::id())->where('orders.id','>',0)
                    ->offset($offset)
                    ->limit($limit);
            })
            ->leftJoin('gruzootpravitel_adresas as o1', 'orders.adres_pogruzke', '=', 'o1.id')
            ->leftJoin('gruzootpravitel_adresas as o2', 'orders.adres_vygruski', '=', 'o2.id')
            ->leftJoin('users as u', 'orders.logist', '=', 'u.id') // Добавлено соединение с таблицей users
            ->with(['ts' => function ($query) {
                $query->select('order_id', TS::raw('COALESCE(SUM(stavka_TS * kol_TS_TS), 0) as total'))
                    ->groupBy('order_id');
            }])
            ->select('orders.*', 'o1.full_name as otkuda', 'o2.full_name as kuda', Orders::raw("CONCAT(u.first_name, ' ', u.last_name, ' ', u.patronymic) as logistFIO")) // Объединение полей пользователя
            ->orderByRaw('CAST(nomer_zayavki AS DECIMAL) DESC')
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
            ->orderByRaw('CAST(nomer_zayavki AS DECIMAL) DESC')
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
            ->orderByRaw('CAST(nomer_zayavki AS DECIMAL) DESC')
            ->count();
    }
    public function issetByColumn($columnName,$columnData)
    {
        $exists = Orders::where($columnName, $columnData)->exists();

        if ($exists) {
            return true;
        } else {
            return false;
        }
    }
    public function getNomenklaturaFileName($id)
    {
        $order = Orders::where('id', $id)->first();
        return $order->nomenklatura;
    }
}
