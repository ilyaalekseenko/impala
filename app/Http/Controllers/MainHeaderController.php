<?php

namespace App\Http\Controllers;

use App\Events\UpdateLogistEvent;
use App\Events\UpdateMainLogistEvent;
use App\Models\Orders;
use App\Models\UnreadHeader;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\LogistService;
use Illuminate\Support\Facades\Auth;


class MainHeaderController extends Controller
{

    private $LogistService;
    private $orderModel;
    private $UnreadHeadersModel;
    private $UserService;


    public function __construct(
        LogistService $LogistService,
        Orders $orderModel,
        UnreadHeader $UnreadHeadersModel,
        UserService $UserService,

    )
    {
        $this->LogistService = $LogistService;
        $this->orderModel = $orderModel;
        $this->UnreadHeadersModel = $UnreadHeadersModel;
        $this->UserService = $UserService;

    }

    //меняем самого логиста на заявке через селект на мордашке
    public function updateOrderLogist(Request $request)
    {
        //получим логиста текущей заявки если он есть
        $logistId=$this->orderModel->getOneColumnByOrderId(request('id'),'logist');
        //назначаем нового логиста
        //назначаем логиста на заявку, либо снимаем его если ноль
        $this->LogistService->setLogistToOrderHeader(request('id'),request('logist'));
        //если логист был, удаляем его оповещения и обновляем ему шапку
        if(($logistId!==0)&&($logistId!==null))
        {
            //предыдущий логист
            //удаляем все не прочитанные ( зелёных ) сообщения текущей заявки у логиста
            $this->UnreadHeadersModel->delUnreadHeaderLogistInModel(request('id'),$logistId);
            //получим количество всех не прочитанных заявок у старого логиста  ( зелёный цвет )
            $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersLogistModel($logistId,'logist');
            //получим количество всех заявок у старого логиста ( белый цвет )
            $mainHeaderArr=$this->orderModel->allHeadersCountModel($logistId,'logist');
            //оповещение тому у кого эта заявка в работе
            broadcast(new UpdateMainLogistEvent($logistId,$unreadHeaderArr,$mainHeaderArr))->toOthers();
        }

        //если в списке выбран логист а не выбрано поле "Логист не выбран"
        if(request('logist')!==0)
        {
            //устанавливаем логиста на колонку Orders и в не прочитанных ( если не было в других колонках )
            $this->LogistService->setLogistToColumnOld(request('id'), request('logist'));
            //получим количество всех не прочитанных заявок у логиста  ( зелёный цвет )
            $unreadHeaderArr = $this->UnreadHeadersModel->getUnreadHeadersLogistModel(request('logist'),'logist');
            //получим количество всех заявок у логиста ( белый цвет )
            $mainHeaderArr = $this->orderModel->allHeadersCountModel(request('logist'),'logist');
            //оповещение тому у кого эта заявка в работе
            broadcast(new UpdateMainLogistEvent(request('logist'), $unreadHeaderArr, $mainHeaderArr))->toOthers();
        }

        //получаем всех админов
        $admins=$this->UserService->getAllAdminsId();
        //обновим шапку у админов при любом изменении
        foreach($admins as $admin)
        {
            //получим количество всех не прочитанных заявок у логиста  ( зелёный цвет )
            $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersLogistModel($admin['id'],'admin');
            //получим количество всех заявок у логиста ( белый цвет )
            $mainHeaderArr=$this->orderModel->allHeadersCountModel($admin['id'],'admin');
            //оповещение тому у кого эта заявка в работе
            broadcast(new UpdateMainLogistEvent($admin['id'],$unreadHeaderArr,$mainHeaderArr))->toOthers();
        }

        return response()->json([
            'status' => 'success',
        ], 200);

    }

    //когда статус заявки меняется по кнопке
    public function orderChangeColumn(Request $request)
    {
        //работа с общими колонками ( белыми )
        //установим в ноль все колонки шапки
        $this->LogistService->setNulltoAllColumnsHeader(request('id'));
        //установить колонку в которой теперь заявка
        $this->LogistService->setOrderColumn(request('id'),request('columnName'));
        //получаем всех админов
        $admins=$this->UserService->getAllAdminsId();

        //если поменялось на назначение ставки или контроль
        if((request('columnName')=='naznachenie_stavki')||(request('columnName')=='kontrol'))
        {
            //получим логиста текущей заявки
            $logistId=$this->orderModel->getOneColumnByOrderId(request('id'),'logist');
            //поставим не прочитанной новую колонку первому а с ним и всем админам, заодно убираем оповещение у логиста
//            $this->UnreadHeadersModel->setUnreadToLogistModel($admins[0]['id'],request('id'),'naznachenie_stavki');
            $this->UnreadHeadersModel->setUnreadToLogistModel($admins[0]['id'],request('id'),request('columnName'));
        //обновим шапку у логиста
            if(($logistId!==null)&&($logistId!==0))
            {
                //работа с не прочитанными
                //обновим всю шапку не прочитанных сразу
                //получим количество всех не прочитанных заявок у логиста  ( зелёный цвет )
                $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersLogistModel($logistId,'logist');
                //получим количество всех заявок у логиста ( белый цвет )
                $mainHeaderArr=$this->orderModel->allHeadersCountModel($logistId,'logist');
                //оповещение тому у кого эта заявка была в работе
                broadcast(new UpdateMainLogistEvent($logistId,$unreadHeaderArr,$mainHeaderArr))->toOthers();
            }
        }
        //если поменялась любая другая колонка кроме назначения ставки, то делаем оповещение логисту
        else
        {
        //получим логиста текущей заявки
        $logistId=$this->orderModel->getOneColumnByOrderId(request('id'),'logist');
        //если он был, делаем ему оповещение
        if(($logistId!==null)&&($logistId!==0))
        {
        //работа с не прочитанными
        //поставим не прочитанной новую колонку логисту
        $this->UnreadHeadersModel->setUnreadToLogistModel($logistId,request('id'),request('columnName'));
        //обновим всю шапку не прочитанных сразу
        //получим количество всех не прочитанных заявок у логиста  ( зелёный цвет )
        $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersLogistModel($logistId,'logist');
        //получим количество всех заявок у логиста ( белый цвет )
        $mainHeaderArr=$this->orderModel->allHeadersCountModel($logistId,'logist');
        //оповещение тому у кого эта заявка была в работе
        broadcast(new UpdateMainLogistEvent($logistId,$unreadHeaderArr,$mainHeaderArr))->toOthers();
        }

        }
        //обновим шапку у админов при любом изменении
        foreach($admins as $admin)
        {
            //получим количество всех не прочитанных заявок у логиста  ( зелёный цвет )
//            $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersLogistModel($admin['id'],'admin');
            $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersAdminModel($admin['id']);
            //получим количество всех заявок ( белый цвет )
            $mainHeaderArr=$this->orderModel->allHeadersCountModel($admin['id'],'admin');
            //оповещение тому у кого эта заявка в работе
            broadcast(new UpdateMainLogistEvent($admin['id'],$unreadHeaderArr,$mainHeaderArr))->toOthers();
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }
    public function headerCounterOrders()
    {
        $user = User::find(Auth::id());
        $role=$user->roles[0]['id'];
        if($role==1)
        {
            //получим количество всех заявок у админа ( белый цвет )
            $mainHeaderArr=$this->orderModel->allHeadersCountModel(Auth::id(),'admin');
            //получим количество всех не прочитанных заявок у админа  ( зелёный цвет )
            $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersLogistModel(Auth::id(),'admin');
        }
        //если роль логист
        if($role==2)
        {
            $mainHeaderArr=$this->orderModel->allHeadersCountModel(Auth::id(),'logist');
            //получим количество всех не прочитанных заявок у админа  ( зелёный цвет )
            $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersLogistModel(Auth::id(),'logist');
        }
        //если роль менеджер
        if($role==3)
        {
            $mainHeaderArr=$this->orderModel->allHeadersCountModel(Auth::id(),'logist');
            //получим количество всех не прочитанных заявок у админа  ( зелёный цвет )
            $unreadHeaderArr=$this->UnreadHeadersModel->getUnreadHeadersLogistModel(Auth::id(),'admin');
        }
        return response()->json([
            'status' => 'success',
            'mainHeaderArr' => $mainHeaderArr,
            'unreadHeaderArr' => $unreadHeaderArr
        ], 201);

    }



}
