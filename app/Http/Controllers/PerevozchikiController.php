<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGruzootpravitelRequest;
use App\Models\FinalGrade;
use App\Models\Perevozka;
use App\Models\PerevozkaBanks;
use App\Models\PerevozkaContacts;
use App\Models\PerevozkaFiles;
use App\Services\PerevozchikService;
use Illuminate\Http\Request;
use App\Traits\DeleteTrait;

class PerevozchikiController extends Controller
{
    use DeleteTrait;
    protected $perevozchikService;
    protected $perevozchikModel;
    protected $perevozchikContacsModel;
    protected $perevozchikBanksModel;
    protected $perevozchikFilesModel;
    protected $finalGradeModel;

    public function __construct(PerevozchikService $perevozchikService, Perevozka $perevozchikModel, PerevozkaContacts $perevozchikContacsModel, PerevozkaBanks $perevozchikBanksModel, PerevozkaFiles $perevozchikFilesModel, FinalGrade $finalGradeModel)
    {
        $this->perevozchikService=$perevozchikService;
        $this->perevozchikModel=$perevozchikModel;
        $this->perevozchikContacsModel=$perevozchikContacsModel;
        $this->perevozchikBanksModel=$perevozchikBanksModel;
        $this->perevozchikFilesModel=$perevozchikFilesModel;
        $this->finalGradeModel=$finalGradeModel;
    }

    public function perevozchiki(Request $request)
    {
        return view('front.perevozchiki')->with('auth_user',  auth()->user());
    }
    public function savePerevozchik(CreateGruzootpravitelRequest $request)
    {
        $currentPerevozchik=request('current_gruzootpravitel_id');
       //если сохраняем нового перевозчика
        if($currentPerevozchik=='')
        {
            //сохраняем нового перевозчика ДОДЕЛАТЬ СОХРАНЕНИЕ КОДА АТИ И КОММЕНТАРИЯ
          $perevozkaID=$this->perevozchikService->saveNewPerevozchik(request('forma'),request('nazvanie'),request('data_registracii'),request('telefon'),request('INN'),request('OGRN'),request('email')
                ,request('generalnii_direktor'),request('telefon_gen_dir'),request('email_gen_dir'),request('yridicheskii_adres'),request('pochtovyi_adres'),request('kontakty'),request('bank_arr')
                ,request('doc_files'),request('gorod_bazirovania'),request('kod_ATI'),request('kommentariy')
            );
            return response()->json([
                'status' => 'success',
                'message' =>'Успешно сохранено',
                'perevozkaID' =>$perevozkaID,
            ], 200);
        }
        //если редактируем существующего перевозчика
        else
        {
            $this->perevozchikService->updatePerevozchik($currentPerevozchik,request('forma'),request('nazvanie'),request('data_registracii'),request('telefon'),request('INN'),request('OGRN'),request('email')
                ,request('generalnii_direktor'),request('telefon_gen_dir'),request('email_gen_dir'),request('yridicheskii_adres'),request('pochtovyi_adres'),request('kontakty'),request('bank_arr')
                ,request('doc_files'),request('gorod_bazirovania'),request('kod_ATI'),request('kommentariy'));
            //удаляем те контакты которых нет ( их удалили в списке )
            //получаем все контакты старые, которые уже были
          $oldContacts=$this->perevozchikContacsModel->getContacts($currentPerevozchik);
            //используем универсальный трейт для удаления старых контаков
          $this->deleteFromDB($oldContacts,request('kontakty'),'PerevozkaContacts');
            //создаём или апдейтим пришедшие с фронта контакты
          $this->perevozchikContacsModel->updateOrCreateContacts($currentPerevozchik,request('kontakty'));
            //получаем все банки старые, которые уже были
            $oldBanks=$this->perevozchikBanksModel->getBanks($currentPerevozchik);
            //используем универсальный трейт для удаления старых контаков
            $this->deleteFromDB($oldBanks,request('bank_arr'),'PerevozkaBanks');
            //создаём или апдейтим пришедшие с фронта контакты
            $this->perevozchikBanksModel->updateOrCreateBanks($currentPerevozchik,request('bank_arr'));
            //обновляем файлы
            $this->perevozchikFilesModel->updateOldFiles(request('doc_files'),$currentPerevozchik);
            return response()->json([
                'status' => 'success',
                'message' =>'Успешно обновлено',
            ], 200);
        }
    }
    public function getPerevozkaListFront()
    {
        //получим список перевозчиков на одной странице
        $perevozkaList=$this->perevozchikModel->getPerevozchiklInOnePage(request('offset'),request('limit'));
        $tipesCount=$this->perevozchikModel->countPerevozka();
        return response()->json([
            'status' => 'success',
            'perevozka' =>$perevozkaList,
            'tipesCount' =>$tipesCount,
            'message' =>'Вид ТС создан',
        ], 200);
    }
    public function deletePerevozka()
    {
        foreach (request('ids') as $id)
        {
            $this->perevozchikService->deletePerevozkaFiles($id);
            $this->perevozchikContacsModel->deleteContact($id);
            $this->perevozchikBanksModel->deleteBank($id);
            $this->perevozchikModel->deletePerevozka($id);
            $this->finalGradeModel->upFinalGradePerevozka($id);
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Грузоотправители удалены',
        ], 200);
    }

    public function getPerevozkaModal(Request $request)
    {
        $id = $request->input('id');
        $perevozka = $this->perevozchikModel->getPerevozka(request('id'));
        $perevozka_contact = $this->perevozchikContacsModel->getContacts($id);
        $perevozka_banks = $this->perevozchikBanksModel->getBanks($id);
        $perevozka_files = $this->perevozchikFilesModel->getFilesList($id);

        return response()->json([
            'status' => 'success',
            'message' =>'Перевозчик успешно получен',
            'perevozka' =>$perevozka[0],
            'perevozka_banks' =>$perevozka_banks,
            'perevozka_contact' =>$perevozka_contact,
            'perevozka_files' =>$perevozka_files,
        ], 200);
    }
    public function getPerevozkaNazvanie()
    {
        $ts=$this->perevozchikModel->getPerevozkaNazvanieInModel(request('nazvanie'));
        if (!$ts->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' =>'Перевозка получена',
                'isset_flag' =>'yes',
                'idTSBack' =>$ts[0]['id'],
                'forma_id' =>$ts[0]['forma_id'],
            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Нет такой перевозки',
            'isset_flag' =>'no',
            'adres_pogruzke' =>0,
        ], 200);
    }

}
