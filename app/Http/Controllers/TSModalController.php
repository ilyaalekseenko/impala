<?php

namespace App\Http\Controllers;

use App\Http\Requests\TSModalCreateRequest;
use App\Models\TSModal;
use App\Models\TSModalFiles;
use App\Models\VidTS;
use App\Services\TSModalService;
use Illuminate\Http\Request;

class TSModalController extends Controller
{
    protected $TSModalService;
    protected $TSModalModel;
    protected $vidTSModel;
    protected $TSModalFilesModel;

    public function __construct( TSModalService $TSModalService, TSModal $TSModalModel, TSModalFiles $TSModalFilesModel, VidTS $vidTSModel)
    {
        $this->TSModalService=$TSModalService;
        $this->TSModalModel=$TSModalModel;
        $this->TSModalFilesModel=$TSModalFilesModel;
        $this->vidTSModel=$vidTSModel;
    }

    public function saveTSModal(TSModalCreateRequest $request)
    {
        $currentTSModal=request('TSModalId');
        //если сохраняем нового ТС номер
        if($currentTSModal=='')
        {
            //сохраняем нового ТС номер
            $perevozkaID=$this->TSModalService->saveNewTSModal(request('tip'),request('marka'),request('nomer'),request('tonn'),request('nomer_documenta'),request('kompaniya'),request('doc_files')
            );
            return response()->json([
                'status' => 'success',
                'message' =>'Успешно сохранено',
                'perevozkaID' =>$perevozkaID,
            ], 200);
        }
        //редактируем ТС номер
        else
        {
            $this->TSModalService->updateTSModal($currentTSModal,request('tip'),request('marka'),request('nomer'),request('tonn'),request('nomer_documenta'),request('kompaniya'));
            $this->TSModalFilesModel->updateOldFiles(request('doc_files'),$currentTSModal);
            return response()->json([
                'status' => 'success',
                'message' =>'Успешно обновлено',
            ], 200);
        }
    }

    public function getTipTSNazvanie()
    {
        $ts=$this->vidTSModel->getVidTSModalNazvanieInModel(request('nazvanie'));
        if (!$ts->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' =>'Тип ТС получен',
                'isset_flag' =>'yes',
                'idTSBack' =>$ts[0]['id'],
            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Нет такого Типа ТС',
            'isset_flag' =>'no',
            'adres_pogruzke' =>0,
        ], 200);
    }

    public function getTSModalNazvanie()
    {
        $ts=$this->TSModalModel->getTSModalNazvanieInModel(request('nazvanie'));
        if (!$ts->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' =>'Перевозка получена',
                'isset_flag' =>'yes',
                'idTSBack' =>$ts[0]['id'],
            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Нет такой перевозки',
            'isset_flag' =>'no',
            'adres_pogruzke' =>0,
        ], 200);
    }
    public function getTSModalList()
    {
        $voditelList=$this->TSModalModel->getTSModalList(request('offset'),request('limit'));
        $tipesCount=$this->TSModalModel->countTSModal();
        return response()->json([
            'status' => 'success',
            'TSModalList' =>$voditelList,
            'tipesCount' =>$tipesCount,
            'message' =>'Список водителей получен',
        ], 200);
    }
    public function getTSModal(Request $request)
    {
        $id = $request->input('id');
        $voditel = $this->TSModalModel->getTSModal(request('id'));
        $voditel_files = $this->TSModalFilesModel->getFilesList($id);
        if($voditel[0]['tip']=='')
        {
            $tipNazvanie='';
        }
        else
        {
            $tipNazvanie = $this->vidTSModel->getTsNameBYId($voditel[0]['tip']);
        }

        return response()->json([
            'status' => 'success',
            'message' =>'TSModal успешно получен',
            'voditel' =>$voditel[0],
            'voditel_files' =>$voditel_files,
            'tipNazvanie' =>$tipNazvanie,
        ], 200);
    }

}
