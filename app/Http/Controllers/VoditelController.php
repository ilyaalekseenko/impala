<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoditelCreateRequest;
use App\Models\Perevozka;
use App\Models\Voditel;
use App\Models\VoditelFiles;
use App\Services\VoditelService;
use Illuminate\Http\Request;

class VoditelController extends Controller
{
    protected $voditelService;
    protected $voditelModel;
    protected $voditelFilesModel;
    protected $perevozchikModel;

    public function __construct( VoditelService $voditelService, Voditel $voditelModel, VoditelFiles $voditelFilesModel,Perevozka $perevozchikModel)
    {
        $this->voditelService=$voditelService;
        $this->voditelModel=$voditelModel;
        $this->voditelFilesModel=$voditelFilesModel;
        $this->perevozchikModel=$perevozchikModel;

    }

    public function  saveVoditel( VoditelCreateRequest $request )
    {
        $currentVoditel=request('voditelId');
        //если сохраняем нового перевозчика
        if($currentVoditel=='')
        {
            //сохраняем нового перевозчика
            $perevozkaID=$this->voditelService->saveNewVoditel(request('FIO'),request('telefon'),request('dataRozdeniya'),request('seriyaPassporta'),request('kemVydan'),request('kogdaVydan'),request('propiska')
                ,request('kompaniya'),request('doc_files')
            );
            return response()->json([
                'status' => 'success',
                'message' =>'Успешно сохранено',
                'perevozkaID' =>$perevozkaID,
            ], 200);
        }
        else
        {
            $this->voditelService->updateVoditel($currentVoditel,request('FIO'),request('telefon'),request('dataRozdeniya'),request('seriyaPassporta'),request('kemVydan'),request('kogdaVydan'),request('propiska'),request('kompaniya'));
            $this->voditelFilesModel->updateOldFiles(request('doc_files'),$currentVoditel);
            return response()->json([
                'status' => 'success',
                'message' =>'Успешно обновлено',
            ], 200);
        }
    }
    public function getVoditelNazvanie()
    {
        $ts=$this->voditelModel->getVoditelNazvanieInModel(request('nazvanie'));
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
    public function getVoditeliList()
    {
        $voditelList=$this->voditelModel->getVoditelList(request('offset'),request('limit'));
        $tipesCount=$this->voditelModel->countVoditel();
        return response()->json([
            'status' => 'success',
            'voditelList' =>$voditelList,
            'tipesCount' =>$tipesCount,
            'message' =>'Список водителей получен',
        ], 200);
    }
    public function getVoditeliModal(Request $request)
    {
        $id = $request->input('id');
        $voditel = $this->voditelModel->getVoditel(request('id'));
        $voditel_files = $this->voditelFilesModel->getFilesList($id);
        //получим название перевозчика(компании)
        $kompaniyaNazvanie=$this->perevozchikModel->getPerevozkaNameBYId($voditel[0]['kompaniya']);

        return response()->json([
            'status' => 'success',
            'message' =>'Перевозчик успешно получен',
            'voditel' =>$voditel[0],
            'voditel_files' =>$voditel_files,
            'kompaniyaNazvanie' =>$kompaniyaNazvanie,
        ], 200);
    }
}
