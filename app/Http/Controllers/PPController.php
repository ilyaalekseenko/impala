<?php

namespace App\Http\Controllers;

use App\Models\Perevozka;
use App\Models\PP;
use App\Models\PPFiles;
use App\Services\PPService;
use Illuminate\Http\Request;

class PPController extends Controller
{

    protected $PPService;
    protected $PPModel;
    protected $PPFilesModel;
    protected $perevozchikModel;

    public function __construct( PPService $PPService, PP $PPModel, PPFiles $PPFilesModel,Perevozka $perevozchikModel)
    {
        $this->PPService=$PPService;
        $this->PPModel=$PPModel;
        $this->PPFilesModel=$PPFilesModel;
        $this->perevozchikModel=$perevozchikModel;
    }

    public function getPP(Request $request)
    {
        $id = $request->input('id');
        $voditel = $this->PPModel->getPP(request('id'));
        $voditel_files = $this->PPFilesModel->getFilesList($id);
        $kompaniyaNazvanie=$this->perevozchikModel->getPerevozkaNameBYId($voditel[0]['kompaniya']);

        return response()->json([
            'status' => 'success',
            'message' =>'TSModal успешно получен',
            'voditel' =>$voditel[0],
            'voditel_files' =>$voditel_files,
            'kompaniyaNazvanie' =>$kompaniyaNazvanie,
        ], 200);
    }

    public function savePP(Request $request)
    {
        $currentTSModal=request('PPId');
        //если сохраняем нового ТС номер
        if($currentTSModal=='')
        {
            //сохраняем нового ТС номер
            $perevozkaID=$this->PPService->saveNewPP($request,request('doc_files'));

            return response()->json([
                'status' => 'success',
                'message' =>'Успешно сохранено',
                'perevozkaID' =>$perevozkaID,
            ], 200);
        }
        //редактируем ПП номер
        else
        {
            $this->PPService->updatePP($currentTSModal,request('marka'),request('nomer'),request('dlina'),request('tonn'),request('nomer_documenta'),request('kompaniya'),request('sts'),request('pts'));
            $this->PPFilesModel->updateOldFiles(request('doc_files'),$currentTSModal);
            return response()->json([
                'status' => 'success',
                'message' =>'Успешно обновлено',
            ], 200);
        }
    }
    public function getPPNazvanie()
    {
        $ts=$this->PPModel->getPPNazvanieInModel(request('nazvanie'));
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
    public function getPPList()
    {
        $voditelList=$this->PPModel->getPPList(request('offset'),request('limit'));
        $tipesCount=$this->PPModel->countPP();
        return response()->json([
            'status' => 'success',
            'TSModalList' =>$voditelList,
            'tipesCount' =>$tipesCount,
            'message' =>'Список ПП получен',
        ], 200);
    }
}
