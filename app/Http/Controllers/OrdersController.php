<?php

namespace App\Http\Controllers;

use App\Events\DeleteOrderEvent;
use App\Events\UpdateLogistEvent;
use App\Events\UpdateNaznachenieStavkiHeaderEvent;
use App\Models\DocList;
use App\Models\DocsTemplate;
use App\Models\FinalGrade;
use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelContact;
use App\Models\Impala;
use App\Models\OplataOrders;
use App\Models\Orders;
use App\Models\Perevozka;
use App\Models\PogruzkaTS;
use App\Models\Role;
use App\Models\TemplateVar;
use App\Models\TS;
use App\Models\PP;
use App\Models\LogistName;
use App\Models\UnreadHeader;
use App\Models\User;
use App\Models\VidPerevozka;
use App\Models\Voditel;
use App\Models\GradePogruzka;
use App\Models\GruzootpravitelAdresa;
use App\Models\VidTS;
use App\Models\TSModal;
use App\Services\DocService;
use App\Services\GruzootpravitelAdresService;
use App\Services\ImportantService;
use App\Services\LogistService;
use App\Services\OplataService;
use App\Services\OrderService;
use App\Services\TSService;
use App\Services\UnreadHeaderService;
use App\Services\UserService;
use App\Services\PogruzkaTSService;
use App\Services\ButtonsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Gotenberg\Gotenberg;
use Gotenberg\Stream;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\Border;
//use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpWord\PhpWord;
use Silverslice\ExcelTemplate\Template;
use Dompdf\Dompdf;
use Carbon\Carbon;


class OrdersController extends Controller
{
    private $orderService;
    private $oplataOrders;
    private $oplataService;
    private $user_mod;
    private $userService;
    private $TSService;
    private $pogruzkaTSService;
    private $order_mod;
    private $docService;
    private $docsTemplMod;
    private $UnreadHeadersService;
    private $ImportantService;
    private $LogistService;
    private $UnreadHeadersModel;
    private $ButtonsService;
    private $gruzootpravitelAdresService;
    private $gradePogruzkaModel;
    private $finalGrade;
    private $gruzootpravitelAdresa;
    private $voditel;
    private $docList;
    private $gruzootpravitel;
    private $impalaModel;
    private $vidPerevozkaModel;
    private $gruzootpravitelContact;
    private $vidTSModel;
    private $TSModal;
    private $PP;


    public function __construct(
        OrderService $orderService,
        OplataOrders $oplataOrders,
        OplataService $oplataService,
        User $user_mod,
        UserService $userService,
        TSService $TSService,
        PogruzkaTSService $pogruzkaTSService,
        Orders $order_mod,
        DocService $docService,
        DocsTemplate $docsTemplMod,
        UnreadHeaderService $UnreadHeadersService,
        ImportantService $ImportantService,
        LogistService $LogistService,
        UnreadHeader $UnreadHeadersModel,
        ButtonsService $ButtonsService,
        GruzootpravitelAdresService $gruzootpravitelAdresService,
        GradePogruzka $gradePogruzkaModel,
        Voditel $voditel,
        FinalGrade $finalGrade,
        GruzootpravitelAdresa $gruzootpravitelAdresa,
        DocList $docList,
        Gruzootpravitel $gruzootpravitel,
        Impala $impala,
        VidPerevozka $vidPerevozka,
        GruzootpravitelContact $gruzootpravitelContact,
        VidTS $vidTS,
        TSModal $TSModal,
        PP $PP
    )
    {
        $this->orderService = $orderService;
        $this->oplataOrders = $oplataOrders;
        $this->oplataService = $oplataService;
        $this->user_mod = $user_mod;
        $this->userService = $userService;
        $this->TSService = $TSService;
        $this->pogruzkaTSService = $pogruzkaTSService;
        $this->order_mod = $order_mod;
        $this->docService = $docService;
        $this->docsTemplMod = $docsTemplMod;
        $this->UnreadHeadersService = $UnreadHeadersService;
        $this->ImportantService = $ImportantService;
        $this->LogistService = $LogistService;
        $this->UnreadHeadersModel = $UnreadHeadersModel;
        $this->ButtonsService = $ButtonsService;
        $this->gruzootpravitelAdresService = $gruzootpravitelAdresService;
        $this->gradePogruzkaModel = $gradePogruzkaModel;
        $this->finalGrade = $finalGrade;
        $this->gruzootpravitelAdresa = $gruzootpravitelAdresa;
        $this->voditel = $voditel;
        $this->docList = $docList;
        $this->gruzootpravitel = $gruzootpravitel;
        $this->impalaModel = $impala;
        $this->vidPerevozkaModel = $vidPerevozka;
        $this->gruzootpravitelContact = $gruzootpravitelContact;
        $this->vidTSModel = $vidTS;
        $this->TSModal = $TSModal;
        $this->PP = $PP;

    }


    public function mainOrders(UserService $userService)
    {
        $user=$userService->getRole();
        return view('front.orders')->with('auth_user',  $user);
    }
    public function createOrders(UserService $userService)
    {
        $user=$userService->getRole();
        return view('front.create_orders')->with('auth_user',  $user);
    }
    public function checkIfOrderIsset(Request $request)
    {
       // $id=$request->input('id');
        $isset =$this->orderService->getfirstOrder(request('id'));

        if ($isset !== null) {
            return response()->json([
                'status' => 'success',
                'data' =>'isset',
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => 'success',
                'data' =>'not',
            ], 200);
        }

    }
    public function get_perevozka_list()
    {
        $perevozka = Perevozka::all();
        return response()->json([
            'status' => 'success',
            'perevozka_list' =>$perevozka,
        ], 200);
    }
    public function getVidPerevozki()
    {
        $perevozka=$this->vidPerevozkaModel->getAllVidPerevozka();
        return response()->json([
            'status' => 'success',
            'perevozka_list' =>$perevozka,
        ], 200);
    }
    public function check_buttons_show(Request $request)
    {
        //логика показа кнопок
        $buttonsStatus=$this->ButtonsService->buttonStatus(request('id'),request('role'));
        $TS= $this->TSService->getTsListByOrderId(request('id'));
        if($TS->isEmpty())
        {
            $buttonsStatus['utverzdenieShowButton']=false;
            $buttonsStatus['vRaboteShowButton']=false;
        }

        return response()->json([
            'status' => 'success',
            'ocenkaShowButton' =>$buttonsStatus['ocenkaShowButton'],
            'utverzdenieShowButton' =>$buttonsStatus['utverzdenieShowButton'],
            'vRaboteShowButton' =>$buttonsStatus['vRaboteShowButton']
        ], 200);

    }
    public function download_current_doc(Request $request)
    {
        $doc_type=$request->input('doc_type');
        $doc= DocsTemplate::where('doc_type','=',$doc_type)->get();
        return response()->json([
            'status' => 'success',
            'file' =>$doc[0]['doc_name'],
        ], 200);

    }
    public function update_unread_status(Request $request)
    {
        //id = order_id
        $id=$request->input('id');
        $column_name=$request->input('column_name');
        //logist = logist_id
        $logist=$request->input('logist_id');
        //выносим в сервис
        if($column_name=='ocenka')
        {
            $this->UnreadHeadersService->updateUnreadStatus('ocenka');

            //$isset= UnreadHeader::where('order_id','=',$id)->where('logist_id',$logist)->where('column_name','ocenka')->get();
           //забрать код ниже в сервис
            //проверка на не прочитанность
            $isset= $this->UnreadHeadersModel->getUnreadHeaderByColumnWhereLogistInModel(request('id'),request('logist_id'),'ocenka');
            if (!$isset->isEmpty())
            {
                $isset= UnreadHeader::where('order_id','=',$id)->where('logist_id',$logist)->where('column_name','ocenka')->delete();
                $orders_count_new=UnreadHeader::where('logist_id',$logist)->where('column_name','ocenka')->count();
                broadcast(new UpdateLogistEvent(0,0,$orders_count_new,$logist))->toOthers();
            }
        }
        if($column_name=='naznachenie_stavki')
        {
            $isset= UnreadHeader::where('order_id','=',$id)->where('column_name','naznachenie_stavki')->get();
            if (!$isset->isEmpty())
            {
                $isset= UnreadHeader::where('order_id','=',$id)->where('column_name','naznachenie_stavki')->delete();
                $orders_count_new=UnreadHeader::where('column_name','naznachenie_stavki')->count();
                broadcast(new UpdateNaznachenieStavkiHeaderEvent())->toOthers();
            }
        }

        return response()->json([
            'status' => 'success',
        ], 200);

    }
    public function update_unread_status_v_rabote(Request $request)
    {
        $id=$request->input('id');
        $column_name=$request->input('column_name');
        $logist=$request->input('logist_id');
        if($column_name=='v_rabote')
        {
            $isset= UnreadHeader::where('order_id','=',$id)->where('logist_id',$logist)->where('column_name','v_rabote')->get();
            if (!$isset->isEmpty())
            {
                $isset= UnreadHeader::where('order_id','=',$id)->where('logist_id',$logist)->where('column_name','v_rabote')->delete();
                $orders_count_new=UnreadHeader::where('logist_id',$logist)->where('column_name','v_rabote')->count();
                broadcast(new UpdateLogistEvent(0,0,$orders_count_new,$logist))->toOthers();
            }
        }
    }
    public function updateOrderLogist(Request $request)
    {
        //назначаем логиста на заявку
        if(request('logist')!==0)
        {
        $setLogist=$this->LogistService->setLogistToOrder(true);
        // UpdateLogistEvent('не прочитанные заявки нового логиста','id нового логиста','не прочитанные заявки старого логиста','id старого логиста')
        broadcast(new UpdateLogistEvent($setLogist['new_logist_orders_count'],request('logist'),$setLogist['old_logist_unread'],$setLogist['old_logist_id']))->toOthers();
        }
        //если приходит то что убрали логиста с заявки вообще ну тоесть ноль,и возможно вариант добавления админом в колонку оценка
        else
        {
        $setLogist=$this->LogistService->setLogistToOrder(true);
        // UpdateLogistEvent('не прочитанные заявки нового логиста','id нового логиста','не прочитанные заявки старого логиста','id старого логиста')
        broadcast(new UpdateLogistEvent(0,0,$setLogist['old_logist_unread'],$setLogist['old_logist_id']))->toOthers();
       }
        return response()->json([
            'status' => 'success',
        ], 200);

    }
    public function updateOrder(Request $request)
    {
        $this->orderService->updateOrderMass();
    }
    public function updateOrderLoc()
    {
        $this->orderService->updateOrderLoc();
    }
    public function update_order_oplata(Request $request)
    {
        $id=$request->input('id');
        $data=$request->input('data');
        $summ_opl=$request->input('summ_opl');
        OplataOrders::where('id', '=', $id)->update([
            $summ_opl =>$data,
        ]);

    }
    public function add_oplata_orders(Request $request)
    {
        $id=$request->input('id');

       $oplata_id= OplataOrders::create([
            'order_id' =>$id,
            'oplata' =>'',
            'summa' =>'',
        ]);
        return response()->json([
            'status' => 'success',
            'message' =>'оплата успешно создана',
            'data' =>$oplata_id,
        ], 200);
    }
    public function delete_oplata_summa(Request $request)
    {
        $id=$request->input('id');
        OplataOrders::where('id','=',$id)->delete();
        return response()->json([
            'status' => 'success',
            'message' =>'оплата успешно удалена'
        ], 200);

    }
    //если заявка уже существует
    public function startGetOldOrder(Request $request)
    {
        $id = $request->input('id');
        $orders_list=$this->orderService->getOrderById(request('id'));
        //получим статус
        $orders_list=$this->orderService->setStatustoOrder($orders_list);
        //получаю название компании заказчика
        if(($orders_list[0]['kompaniya_zakazchik'])!==null)
        {
           $gruzName=$this->gruzootpravitel->getGruzootpravitelByIdInModel($orders_list[0]['kompaniya_zakazchik']);
           $orders_list[0]['kompaniya_zakazchik_name']=$gruzName[0]['nazvanie'];
        }
        else
        {
            $orders_list[0]['kompaniya_zakazchik_name']='';
        }
        //получаю менеджера заказчика
        if(($orders_list[0]['menedzer_zakazchik'])!==null)
        {
            $manager=$this->gruzootpravitelContact->getContact($orders_list[0]['menedzer_zakazchik']);
            $orders_list[0]['menedzer_zakazchik_name']=$manager[0]['FIO'];
            $orders_list[0]['menedzer_zakazchik']=$manager[0]['id'];
        }
        else
        {
            $orders_list[0]['menedzer_zakazchik']='';
            $orders_list[0]['menedzer_zakazchik_name']='';
        }
        //получить новый полный адрес погрузки и выгрузки
        if(($orders_list[0]['adres_pogruzke'])==null)
        {
            $adres_pogruzke_show='';
        }
        else
        {
            $adres_pogruzke_show=$this->gruzootpravitelAdresService->getOneAdresForSearch($orders_list[0]['adres_pogruzke']);
        }
      //  $adres_pogruzke_show=$this->orderService->setAdressPogrVygrShow($orders_list[0]['adres_pogruzke']);
        if(($orders_list[0]['adres_vygruski'])==null)
        {
            $adres_vygruski_show='';
        }
        else
        {
            $adres_vygruski_show=$this->gruzootpravitelAdresService->getOneAdresForSearch($orders_list[0]['adres_vygruski']);
        }
        //$adres_vygruski_show=$this->orderService->setAdressPogrVygrShow($orders_list[0]['adres_vygruski']);
        $oplata_list =$this->oplataOrders->getOplataByOrderIdInModel(request('id'));
        $orders_list[0]['oplata']=$this->oplataService->setOplata($oplata_list);
        //получаем юзера по id логиста
        $log_name= $this->user_mod->getUserByIdInModel($orders_list[0]['logist']);
        //присваиваем имя
        $orders_list[0]['logist_name']=$this->userService->setNameToUser($log_name);
       //получаем список ТС по order_id
        $TS_list= $this->TSService->getTsListByOrderId($id);
        //получаем список адресов погрузки и выгрузки
        foreach ($TS_list as $ts)
        {
            $ts['adres_pogruzki_TS']= $this->pogruzkaTSService->tsListPogruzkaGet($id,$ts['id_ts'],'1');
            $ts['adres_vygr_TS']= $this->pogruzkaTSService->tsListPogruzkaGet($id,$ts['id_ts'],'2');
            //добавляем название к адресу погрузки
            foreach ($ts['adres_pogruzki_TS'] as $one_adres) {
                if(($one_adres['adres_pogruzki'])==null)
                {
                    $one_adres['adres_pogruzke_show']='';
                }
                else
                {
                    $one_adres['adres_pogruzke_show']=$this->gruzootpravitelAdresService->getOneAdresForSearch($one_adres['adres_pogruzki']);
                }
            }
            //добавляем название к адресу выгрузки
            foreach ($ts['adres_vygr_TS'] as $one_adres) {
                if(($one_adres['adres_pogruzki'])==null)
                {
                    $one_adres['adres_vygruzki_show']='';
                }
                else
                {
                    $one_adres['adres_vygruzki_show']=$this->gruzootpravitelAdresService->getOneAdresForSearch($one_adres['adres_pogruzki']);
                }            }
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Заявка успешно получена',
            'data' =>$orders_list,
            'TS_list' =>$TS_list,
            'adres_pogruzke_show' =>$adres_pogruzke_show,
            'adres_vygruski_show' =>$adres_vygruski_show,
        ], 200);
    }
    //если новая заявка
    public function startNewOrder(Request $request)
    {
        $orders=$this->orderService->newOrder(request('data_vneseniya'));
        return response()->json([
            'status' => 'success',
            'message' =>'Заявка успешно создана',
            'data' =>$orders,
        ], 200);
    }
    public function storeXlsx(Request $request)
    {
        //сохраним в БД
        $this->order_mod->updateOneFieldInOrderInModel(request('order_id'),'nomenklatura',request('file_name'));
        //сохраним в проекте
        $this->docService->storeDoc(request('file_xlsx'),'/images/orders_xlsx/',$request['order_id'].'__'.$request['full_name']);
        return response()->json([
            'status' => 'success',
            'message' =>'Файл xlsx успешно сохранён',
        ], 200);
    }
    public function store_doc(Request $request)
    {
        $this->docService->storeDoc(request('file_xlsx'),'/grade_doc/',$request['order_id'].'__'.$request['full_name']);
//        $request['file_xlsx']->move(public_path('/grade_doc/'), $request['order_id'].'__'.$request['full_name']);
        return response()->json([
            'status' => 'success',
            'message' =>'Файл xlsx успешно сохранён',
        ], 200);
    }
    public function storeDocTempl(Request $request)
    {
            $isset = $this->docsTemplMod->getFirstInModel('doc_type',request('doc_type'));
            if ($isset !== null) {
                //удаляем документ
                $this->docService->delDoc(public_path() . "/templates/" . $isset['doc_name']);
                //обновляем
                $this->docsTemplMod->updateOneFieldInModel($isset['id'],'doc_name',request('full_name'));
            }
            else{
                $this->docsTemplMod->createDocFieldsInModel(request('doc_type'),request('full_name'));
            }
            //сохраняем
        $this->docService->storeDoc(request('file_xlsx'),'/templates/',request('full_name'));
            return response()->json([
            'status' => 'success',
            'message' =>'Файл успешно сохранён',
        ], 200);
    }
    public function get_templ_names(Request $request)
    {
        $TH = DocsTemplate::where('doc_type','TH')->get();
        $DOV = DocsTemplate::where('doc_type','DOV')->get();
        $ZAI = DocsTemplate::where('doc_type','ZAI')->get();
        if ($TH->isEmpty()) {
            $TH='';
        }
        else
        {
            $TH=$TH[0]['doc_name'];
        }
        if ($DOV->isEmpty()) {
            $DOV='';
        }
        else
        {
            $DOV=$DOV[0]['doc_name'];
        }
        if ($ZAI->isEmpty()) {
            $ZAI='';
        }
        else
        {
            $ZAI=$ZAI[0]['doc_name'];
        }


        return response()->json([
            'status' => 'success',
            'TH' =>$TH,
            'DOV' =>$DOV,
            'ZAI' =>$ZAI,
        ], 200);
    }
    public function get_xlsx_file($filename)
    {
        return response()->download(public_path('images/orders_xlsx/'.$filename));
    }
    public function get_finall_doc_pdf_file($filename)
    {
        return response()->download(public_path('templates/'.$filename));

    }
    public function download_xlsx_orders(Request $request)
    {
        $id = $request->input('id');
        $order = Orders::where('id', '=', $id) ->get();
        $file=$id.'__'.$order[0]['nomenklatura'];
        return response()->json([
            'status' => 'success',
            'file' =>$file,
        ], 200);

    }
    public function get_finall_doc_pdf(Request $request)
    {
        ini_set('max_execution_time', 180);

//            return response()->download(public_path('templates/отчёт.txt'))


        $doc_type = $request->input('doc_type');

        $id = $request->input('order_id');
        $orders_list = Orders::where('id', '=', $id) ->get();

        //получаю порядковый номер
        $porNomer=$this->docList->getPorNomer($doc_type);

        $finalGradeTS=$this->finalGrade->getIdByGrade(request('order_id'),request('id_ts'));
        if($doc_type=='1')
        {
            $TH = DocsTemplate::where('doc_type','TH')->get();

            //получаю дату погрузки

            $pattern = '/(\d{2}\.\d{2}\.\d{4})/';
            $string = $orders_list[0]['data_pogruzki'];

            if($orders_list[0]['data_pogruzki']==null)
            {
                $data_pogruzki='';
            }
           else
           {
               if (preg_match($pattern, $string, $matches)) {
                   $data_pogruzki = $matches[1];
               }
           }

            //получаю данные ГО
            if($orders_list[0]['adres_pogruzke']==null)
            {
                $GOdannye='';
            }
            else
            {
                $GOdannye=$this->gruzootpravitelAdresService->getGObyAdres($orders_list[0]['adres_pogruzke']);
            }
            //получаю данные ГП
            if($orders_list[0]['adres_vygruski']==null)
            {
                $GPdannye='';
            }
            else
            {
                $GPdannye=$this->gruzootpravitelAdresService->getGObyAdres($orders_list[0]['adres_vygruski']);
            }
            return dd($orders_list[0]);
            if($orders_list[0]['kompaniya_zakazchik']==null)
            {
                $zakazchik='';
            }
            else
            {
                $zakazchik=$this->gruzootpravitel->getFullNameGruzootpravitel($orders_list[0]['kompaniya_zakazchik']);
            }
            return dd('TIE4');
            //получаю адреса погрузки и выгрузки
            //возвращает массив в первом значении погрузка, во втором выгрузка
            $pogruzkaArr=$this->pogruzkaTSService->pogruzkaName();
            //получаю количество грузомест у данного ТС (цифра + словом )
            $kolMest=$this->finalGrade->getKolMest(request('order_id'),request('id_ts'));

            //получаю перевозчика
            $organizacia=$this->impalaModel->getAdres();
            if ($organizacia->isNotEmpty()) {
                $organizacia=$organizacia[0]['adres'];
            }
            else
            {
                $organizacia='';
            }
            //получаю водителя
            if($finalGradeTS[0]['voditel']==null)
            {
                $voditel='';
            }
            else
            {
                $voditel=$this->voditel->getFullVoditelBYId($finalGradeTS[0]['voditel']);
            }
            //получаю тип ТС
            if($finalGradeTS[0]['voditel']==null)
            {
                $vidTS='';
            }
            else
            {
                $vidTS=$this->vidTSModel->getVidTSById($finalGradeTS[0]['vid_TS']);
            }
            //получаю номер ТС и номер ПП ( если есть )
            if($finalGradeTS[0]['nomer_TS']==null)
            {
                $nomerTS='';
            }
            else
            {
                $nomerTS=$this->TSModal->getTSModalNameBYId($finalGradeTS[0]['nomer_TS']);
            }
            if($finalGradeTS[0]['nomer_PP']==null)
            {
                $nomerPP='';
            }
            else
            {
                $nomerPP=$this->PP->getPPNameBYId($finalGradeTS[0]['nomer_PP']);
            }
            if($nomerPP=='')
            {
                $nomerTSPP=$nomerTS;
            }
            else
            {
                $nomerTSPP=$nomerTS.'/'.$nomerPP;
            }

            //получаю имя шаблона
            $TH=$TH[0]['doc_name'];
            //формирую имя документа
            if($orders_list[0]['id']==null)
            {
                $orders_list[0]['id']='';
            }
            if($orders_list[0]['data_pogruzki']==null)
            {
                $orders_list[0]['data_pogruzki']='';
            }
            $fileName='TH_'.$orders_list[0]['id'].'_'.$orders_list[0]['data_pogruzki'].'.xlsx';
            $template = new Template();
            $template->open(public_path('templates/'.$TH))

                ->replace('data_pogruzki', $data_pogruzki)
                ->replace('por_nomer',$porNomer)
                ->replace('zak',$zakazchik)
                ->replace('GP',$GPdannye)
                ->replace('GO',$GOdannye)
                ->replace('adres_pogruzki', $pogruzkaArr[0])
                ->replace('adres_vygruzki', $pogruzkaArr[1])
                ->replace('kol_gruzomest', $kolMest)
                ->replace('organizacia', $organizacia)
                ->replace('voditel', $voditel)
                ->replace('vidTS', $vidTS)
                ->replace('nomerTSPP', $nomerTSPP)
                ->save(public_path('templates/'.$fileName));
            //добавляю запись в таблицу doc_lists с порядковым номером и датой создания
              $this->docList->upPorNomer($doc_type,$porNomer,$fileName);

            return response()->json([
                'status' => 'success',
                'file' =>$fileName,
            ], 200);
        }
        if($doc_type=='2')
        {
            //получаю поставщика
            if($orders_list[0]['adres_pogruzke']==null)
            {
                $postavshik='';
            }
            else
            {
             $gruz=$this->gruzootpravitelAdresa->getAdresByIdWithGruz($orders_list[0]['adres_pogruzke']);
                 if($gruz->isNotEmpty())
                 {
                     $postavshik=$this->gruzootpravitel->getGruzootpravitelByIdInModel($gruz[0]['gruzootpravitel_id']);
                     if($postavshik->isNotEmpty())
                     {
                         $postavshik=$postavshik[0]['forma_id'].' '.$postavshik[0]['nazvanie'];
                     }
                 }
                 else
                 {
                     $postavshik='';
                 }
            }
            //высчитываем срок действия

            if($orders_list[0]['data_pogruzki']==null)
            {
                $carbonDate='';
                $srok_deist='';
                $textDate='';
                $textDateSrokDeistv='';
            }
            else
            {
                $carbonDate = Carbon::createFromFormat('d.m.Y', $orders_list[0]['data_pogruzki']);
                $srok_deist = $carbonDate->addMonth()->format('d.m.Y');
                //получаю текстовый срок действия
                $textDate=$this->orderService->convertDateMonth($orders_list[0]['data_pogruzki']);
                $textDateSrokDeistv=$this->orderService->convertDateMonth($srok_deist);
            }

            if($finalGradeTS[0]['voditel']==null)
            {
                $voditel='';
                $voditelFull[0]['kemVydan']='';
                $voditelFull[0]['kogdaVydan']='';
                $voditelFull[0]['seriyaPassporta']='';
            }
            else
            {
                //получаю ИМЯ ФАМИЛИЯ ОТЧЕСТВО водителя
                $voditel=$this->voditel->getVoditelNameBYId($finalGradeTS[0]['voditel']);
                //получаю полные данные водителя
                $voditelFull=$this->voditel->getVoditel($finalGradeTS[0]['voditel']);
            }
            $organizacia=$this->impalaModel->getAdres();
            if ($organizacia->isNotEmpty()) {
                $organizacia=$organizacia[0]['adres'];
            }
            else
            {
                $organizacia='';
            }
           // $organizacia='ООО "ИМПАЛА" , ИНН/КПП 7839103970/780201001, 194358, Санкт-Петербург г, п Парголово, ул Заречная, д. 45, к. 1, стр. 1, кв. 1238';

            $TH = DocsTemplate::where('doc_type','DOV')->get();
            $TH=$TH[0]['doc_name'];
            $template = new Template();
            $template->open(public_path('templates/'.$TH))
                ->replace('por_nomer', $porNomer)
                ->replace('data_vydachi', $orders_list[0]['data_pogruzki'])
                ->replace('srok_deist', $srok_deist)
                ->replace('voditel', $voditel)
                ->replace('postav', $postavshik)
                ->replace('organizacia', $organizacia)
                ->replace('vod_passport', $voditelFull[0]['seriyaPassporta'])
                ->replace('vod_passport_kem', $voditelFull[0]['kemVydan'])
                ->replace('vod_passport_kogda', $voditelFull[0]['kogdaVydan'])
                ->replace('data_vydachi_text', $textDate.' г')
                ->replace('deistv_do', $textDateSrokDeistv.' г')
                ->save(public_path('templates/fin_DOV.xlsx'));
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $phpWord = $reader->load(public_path('templates/fin_DOV.xlsx'));
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($phpWord);
            $writer->save(public_path('templates/DOV.pdf'));

            return response()->json([
                'status' => 'success',
                'file' =>'DOV.pdf',
            ], 200);
        }
        if($doc_type=='3')
        {
            $TH = DocsTemplate::where('doc_type','ZAI')->get();
            $TH=$TH[0]['doc_name'];

            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('templates/'.$TH));
            $templateProcessor->
            setValue(array('{{company}}'), array('Developer'));
            $templateProcessor->saveAs(public_path('templates/fin_ZAI.docx'));

            $domPdfPath = base_path('vendor/mpdf/mpdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('MPDF');
            $wordPdf = \PhpOffice\PhpWord\IOFactory::load(public_path('templates/fin_ZAI.docx'));
            $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordPdf , 'PDF');
            $pdfWriter->save(public_path('templates/ZAI.pdf'));


            return response()->json([
                'status' => 'success',
                'file' =>'ZAI.pdf',
            ], 200);
        }
    }


    public function getOrderStatus(Request $request)
    {
        $resArr=$this->orderService->setStatustoOrder(request('orders'));

    }
    public function getOrderlist(Request $request)
    {
        //получаем название статуса пользователя
      $userStatus=$this->user_mod->getUserStatusName();
      //получаем данные из списка заявок
      $resArr= $this->orderService->getOrdersListService($userStatus,request('columnName'),request('offset'),request('limit'));
      //посчитаем сколько всего записей выводить
      $count=$this->orderService->countOrdersListService($userStatus,request('columnName'));
      //добавим статус для каждой заявки
      $resArr=$this->orderService->setStatustoOrder($resArr);
        return response()->json([
            'status' => 'success',
            'list_colored' => $resArr,
            'tipesCount' => $count,
           // 'order_by' => $order_by,
           // 'role' => $role,
//            'res'=>$list_colored_imp_old,
//            'res1'=>$old_imp['id']
        ], 201);

    }

    public function get_orders_list_new(Request $request)
    {
        $offset =  $request->input('offset');
        $limit =  $request->input('limit');

        //выборка по фильтру из шапки
        //0 - журнал заявок
        //1 - оценка
        //2 - назначение ставки
        //3 - в работе
        //4 - контроль
        //5 - завершён
        $order_by =  $request->input('order_by');
        $user = User::find(Auth::id());
        $role=$user->roles[0]['id'];


        //разбираем важные
        //получаем количество всех важных записей
        $all_imp = Orders::where('important', 1)
            //если сортировка по колонке оценка и юзер админ, отдаём оценки минус назначение ставки
            ->when($order_by==1&&$role==1, function($q){
                return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер логист, отдаём только его заявки минус назначение ставки
            ->when($order_by==1&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
            ->when($order_by==1&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
            ->when($order_by==2&&$role==1, function($q){
                return $q->where('naznachenie_stavki', '=', 1);
            })
            //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
            ->when($order_by==2&&$role==2, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
            ->when($order_by==2&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке в работе и юзер админ, отдаём все в работе
            ->when($order_by==3&&$role==1, function($q){
                return $q->where('v_rabote', '=', 1);
            })
            //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
            ->when($order_by==3&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
            ->when($order_by==3&&$role==3, function($q){
                return $q->where('id', 0);
            })
            ->count();

        //получаем все важные сначала
        $list_colored_imp = Orders::where('important', 1)
            //если сортировка по колонке оценка и юзер админ, отдаём оценки минус назначение ставки
            ->when($order_by==1&&$role==1, function($q){
                return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер логист, отдаём только его заявки минус назначение ставки
            ->when($order_by==1&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
            ->when($order_by==1&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
            ->when($order_by==2&&$role==1, function($q){
                return $q->where('naznachenie_stavki', '=', 1);
            })
            //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
            ->when($order_by==2&&$role==2, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
            ->when($order_by==2&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке в работе и юзер админ, отдаём все в работе
            ->when($order_by==3&&$role==1, function($q){
                return $q->where('v_rabote', '=', 1);
            })
            //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
            ->when($order_by==3&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
            ->when($order_by==3&&$role==3, function($q){
                return $q->where('id', 0);
            })
            ->get();
        $not_empty_flag=false;
//        if($all_imp>$offset&&($limit+$offset)<=$all_imp)
        if($all_imp>$offset)
        {
            $dif=$limit+$offset-$all_imp;

            if($dif>0)
            {
                if($all_imp-$offset>0)
                {
                    $temp_offset=0;
                }
                //получаем все не важные записи с листа
                $list_colored = Orders::where('id', '>=', 0)
                    ->where('important',null)
                    //если сортировка по колонке оценка и юзер админ
                    ->when($order_by==1&&$role==1, function($q){
                        return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
                    })
                    //если сортировка по колонке оценка и юзер логист, отдаём только его заявки
                    ->when($order_by==1&&$role==2, function($q){
                        return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
                    })
                    //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
                    ->when($order_by==1&&$role==3, function($q){
                        return $q->where('id', 0);
                    })
                    //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
                    ->when($order_by==2&&$role==1, function($q){
                        return $q->where('naznachenie_stavki', '=', 1);
                    })
                    //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
                    ->when($order_by==2&&$role==2, function($q){
                        return $q->where('id', 0);
                    })
                    //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
                    ->when($order_by==2&&$role==3, function($q){
                        return $q->where('id', 0);
                    })
                    //если сортировка по колонке в работе и юзер админ, отдаём все в работе
                    ->when($order_by==3&&$role==1, function($q){
                        return $q->where('v_rabote', '=', 1);
                    })
                    //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
                    ->when($order_by==3&&$role==2, function($q){
                        return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
                    })
                    //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
                    ->when($order_by==3&&$role==3, function($q){
                        return $q->where('id', 0);
                    })
                    ->offset($temp_offset)
                    ->limit($dif)
                    ->get();
                $not_empty_flag=true;
            }
        }
      else
      {
          $list_colored = Orders::where('id', '>=', 0)
              ->where('important',null)
              //если сортировка по колонке оценка и юзер админ
              ->when($order_by==1&&$role==1, function($q){
                  return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
              })
              //если сортировка по колонке оценка и юзер логист, отдаём только его заявки
              ->when($order_by==1&&$role==2, function($q){
                  return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
              })
              //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
              ->when($order_by==1&&$role==3, function($q){
                  return $q->where('id', 0);
              })
              //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
              ->when($order_by==2&&$role==1, function($q){
                  return $q->where('naznachenie_stavki', '=', 1);
              })
              //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
              ->when($order_by==2&&$role==2, function($q){
                  return $q->where('id', 0);
              })
              //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
              ->when($order_by==2&&$role==3, function($q){
                  return $q->where('id', 0);
              })
              //если сортировка по колонке в работе и юзер админ, отдаём все в работе
              ->when($order_by==3&&$role==1, function($q){
                  return $q->where('v_rabote', '=', 1);
              })
              //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
              ->when($order_by==3&&$role==2, function($q){
                  return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
              })
              //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
              ->when($order_by==3&&$role==3, function($q){
                  return $q->where('id', 0);
              })
              ->offset($offset-$all_imp)
              ->limit($limit)
              ->get();
          $not_empty_flag=true;
      }

        $res_arr=[];
        if($all_imp>$offset)
        {
        //добавляем важные записи в результирующий массив
            foreach ($list_colored_imp as $imp)
            {
                array_push($res_arr,$imp);
            }
            $res_arr = array_splice($res_arr, $offset, $limit);
        }
        if($not_empty_flag)
        {
            //добавляем все остальные записи в результирующий массив
            foreach ($list_colored as $colored)
            {
                array_push($res_arr,$colored);
            }
        }

        $count = Orders::where('id', '>=', 0)
            //если сортировка по колонке оценка и юзер админ
            ->when($order_by==1&&$role==1, function($q){
                return $q->where('logist', '<>', null)->where('logist', '<>', 0)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер логист, отдаём только его заявки
            ->when($order_by==1&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке оценка и юзер менеджер, отдаём ноль
            ->when($order_by==1&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер админ, отдаём все назначения ставки
            ->when($order_by==2&&$role==1, function($q){
                return $q->where('naznachenie_stavki', '=', 1);
            })
            //если сортировка по колонке назначение ставки и юзер логист отдаём ноль
            ->when($order_by==2&&$role==2, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке назначение ставки и юзер менеджер отдаём ноль
            ->when($order_by==2&&$role==3, function($q){
                return $q->where('id', 0);
            })
            //если сортировка по колонке в работе и юзер админ, отдаём все в работе
            ->when($order_by==3&&$role==1, function($q){
                return $q->where('v_rabote', '=', 1);
            })
            //если сортировка по колонке в работе и юзер логист отдаём то что в работе у логиста и уже не в назначении ставки
            ->when($order_by==3&&$role==2, function($q){
                return $q->where('logist', Auth::id())->where('v_rabote', '=', 1)->where('naznachenie_stavki', '=', null);
            })
            //если сортировка по колонке в работе ставки и юзер менеджер отдаём ноль
            ->when($order_by==3&&$role==3, function($q){
                return $q->where('id', 0);
            })
            ->count();
        //получаем имя грузоотправителя
        $gruzootpravitel = Gruzootpravitel::all();

        //конец выборки по колонки 0 - журнал заявок

        return response()->json([
            'status' => 'success',
            'list_colored' => $res_arr,
            'tipes_count' => $count,
            'order_by' => $order_by,
            'role' => $role,
//            'res'=>$list_colored_imp_old,
//            'res1'=>$old_imp['id']
        ], 201);
    }

    public function get_orders_list(Request $request)
    {
        $offset =  $request->input('offset');
        $limit =  $request->input('limit');
        //получаем все важные сначала
        $list_colored_imp = Orders::where('important', 1)
            ->offset($offset)
            ->limit($limit)
            ->get();
        //получаем количество важных в том листе где будем отрисовывать результат
        $list_important_count_in_page = Orders::where('id', '>=', 0)
            ->offset($offset)
            ->limit($limit)
            ->get();
        $current_count=0;
        foreach ($list_important_count_in_page as $colored)
        {
            if($colored['important']==1)
            {
                $current_count++;
            }
        }
        //получаем количество важных записей на предыдущих листах
//        $old_imp = Orders::where('id', '>=', 0)
//            ->offset($offset)
//            ->first();
//      $list_colored_imp_old = Orders::where('important', 1)
//            ->where('id','<',$old_imp['id'])
//            ->count();
//
//        //получаем количество всех важных записей
//        $all_imp = Orders::where('important', 1)->count();
//        //проверяем а был ли сдвиг на предыдущем листе
//        $sdvig=$offset-$limit-$all_imp;
//        if($sdvig>=0)
//        {
//            $list_colored_imp_old=0;
//        }
        //получаем все записи с листа
        $list_colored = Orders::where('id', '>=', 0)
            ->offset($offset)
            //плюс количество своих записей
            ->limit($limit+$current_count)
            ->get();
        $res_arr=[];
        //добавляем важные записи в результирующий массив
        foreach ($list_colored_imp as $imp)
        {
            array_push($res_arr,$imp);
        }
        //добавляем все остальные записи в результирующий массив кроме важных
        foreach ($list_colored as $colored)
        {
            if($colored['important']!=1)
            {
                array_push($res_arr,$colored);
            }
        }
        //вырезаем все лишние записи
        $res_arr = array_splice($res_arr, 0, $limit);
        $count = Orders::where('id', '>=', 0)
            ->count();
        return response()->json([
            'status' => 'success',
            'list_colored' => $res_arr,
            'tipes_count' => $count
//            'res'=>$list_colored_imp_old,
//            'res1'=>$old_imp['id']
        ], 201);
    }
    public function deleteOrders(Request $request)
    {
        $orders_id =  $request->input('orders_id');
        //удаляем все заявки по id
        $this->order_mod->whereInDeleteInModel(request('orders_id'));
        //удаляем final grades они же ставки
        $this->finalGrade->delFinalGradeByID($orders_id);
        //удаление не прочитанных шапок
        $this->UnreadHeadersService->delUnreadHeaders(request('orders_id'));
        broadcast(new DeleteOrderEvent($orders_id))->toOthers();
        return response()->json([
            'status' => 'success',
            'message' => 'заявки успешно удалены',
        ], 201);
    }
    public function importantMark(Request $request)
    {
        $this->ImportantService->importantMarkGlobal();
        return response()->json([
            'status' => 'success',
            'message' => 'заявки успешно обновлены',
        ], 201);
    }
    public function markAsImportant(Request $request)
    {
        foreach (request('orders_id') as $order)
        {
            $single_order=$this->orderService->getOrderById($order);
            if($single_order[0]['important']==1)
            {
                $this->order_mod->updateOneFieldInOrderInModel($order,'important',null);
            }
            else
            {
                $this->order_mod->updateOneFieldInOrderInModel($order,'important',1);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'заявки успешно обновлены',
        ], 201);
    }
    public function up_gruzoot_from_select(Request $request)
    {
        $order_id =  $request->input('order_id');
        $adres_pogruzke =  $request->input('adres_id');
        $column_name =  $request->input('column_name');
        Orders::where('id', $order_id)->update([
            $column_name =>$adres_pogruzke,
        ]);
    }
    public function updateOrderNomerZaprosa()
    {
            $issetMod=$this->order_mod->issetByColumn('nomer_zayavki',request('nomer_zayavki'));
            //если такой номер заявки уже есть
            if($issetMod)
               {
                   return response()->json([
                       'status' => 'success',
                       'updated' => 'false',
                   ], 201);
               }
            else
            {
                //обновляем заявку
                $this->order_mod->updateOneFieldInOrderInModel(request('id'),'nomer_zayavki',request('nomer_zayavki'));
                return response()->json([
                    'status' => 'success',
                    'updated' => 'true',
                ], 201);
            }
    }
//    public function checkOrderStatusName(Request $request)
//    {
//        $order=$this->orderService->getStatusName(request('orderId'));
//
//    }
}
