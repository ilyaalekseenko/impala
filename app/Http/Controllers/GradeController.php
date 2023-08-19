<?php

namespace App\Http\Controllers;

use App\Events\UpdateNaznachenieStavkiHeaderEvent;
use App\Events\UpdateVRaboteHeaderEvent;
use App\Models\FinalGrade;
use App\Models\GradeDocuments;
use App\Models\GradePogruzka;
use App\Models\GradeSumma;
use App\Models\Gruzootpravitel;
use App\Models\NaznachenieStavki;
use App\Models\Orders;
use App\Models\Perevozka;
use App\Models\PogruzkaTS;
use App\Models\TemplateVar;
use App\Models\TS;
use App\Models\TSModal;
use App\Models\UnreadHeader;
use App\Models\User;
use App\Models\UserRole;
use App\Models\VidTS;
use App\Models\Voditel;
use App\Models\PP;
use App\Services\DocService;
use App\Services\GruzootpravitelAdresService;
use App\Services\TSService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use ZipArchive;
use App\Services\UserService;


class GradeController extends Controller
{
    private $userService;
    private $gruzootpravitelAdresService;
    private $docService;
    private $vidTSModel;
    private $perevozkaModel;
    private $voditelModel;
    private $gradePogruzkaModel;
    private $gradeSummaModel;
    private $finalGradeModel;
    private $PPModel;
    private $TSModal;
    private $searchService;


    public function __construct(
        UserService $userService,
        DocService $docService,
        GruzootpravitelAdresService $gruzootpravitelAdresService,
        VidTS $vidTS,
        Perevozka $perevozkaModel,
        Voditel $voditelModel,
        TSModal $TSModalModel,
        PP $PPModel,
        GradePogruzka $gradePogruzkaModel,
        GradeSumma $gradeSummaModel,
        FinalGrade $finalGradeModel,
        SearchService $searchService
    )
    {
        $this->userService = $userService;
        $this->gruzootpravitelAdresService = $gruzootpravitelAdresService;
        $this->vidTSModel = $vidTS;
        $this->perevozkaModel = $perevozkaModel;
        $this->voditelModel = $voditelModel;
        $this->TSModalModel = $TSModalModel;
        $this->PPModel = $PPModel;
        $this->docService = $docService;
        $this->gradePogruzkaModel = $gradePogruzkaModel;
        $this->gradeSummaModel = $gradeSummaModel;
        $this->finalGradeModel = $finalGradeModel;
        $this->searchService = $searchService;
    }

    public function get_template_vars()
    {
        $data1=TemplateVar::where('doc_type', '1')->get();
        $data2=TemplateVar::where('doc_type', '2')->get();
        $data3=TemplateVar::where('doc_type', '3')->get();

        return response()->json([
            'status' => 'success',
            'templ_vars1'   => $data1,
            'templ_vars2'   => $data2,
            'templ_vars3'   => $data3,
            'success'   => 'yes',
        ], 200);
    }
    public function showGrade(Request $request)
    {
        $user=$this->userService->getRole();
//        return view('front.grade')->with('auth_user',auth()->user());
        return view('front.grade')->with('auth_user',$user);
    }
    public function save_start_summa(Request $request)
    {
        $grade_id=$request->input('grade_id');
        $id_ts=$request->input('id_ts');
        GradeSumma::create([
            'grade_id' => $grade_id,
            'id_ts' =>$id_ts,
            'id_summa' =>0,
            'summa' => 0,
            'data' => '',
        ]);
        return response()->json([
            'status' => 'success',
            'message' =>'Сумма успешно сохранена',
        ], 200);
    }
    public function add_to_naznachenie_stavki(Request $request)
    {
        $order_id=$request->input('order_id');

        Orders::where('id', '=', $order_id)->update(
                [
                    'naznachenie_stavki'=> 1
                ],
            );
        UnreadHeader::firstOrCreate([
            'logist_id' =>0,
            'column_name' =>'naznachenie_stavki',
            'order_id' =>$order_id,
        ]);
        broadcast(new UpdateNaznachenieStavkiHeaderEvent())->toOthers();
        return response()->json([
            'status' => 'success',
            'message' =>'Заявка успешно сохранена в назначение ставки',
        ], 200);
    }
    public function add_to_work(Request $request)
    {
        $order_id=$request->input('order_id');

        Orders::where('id', '=', $order_id)->update(
            [
                'naznachenie_stavki'=> null,
                'v_rabote'=> 1,
            ],
        );
        $logist_id=Orders::where('id', '=', $order_id)->get();
        UnreadHeader::firstOrCreate([
            'logist_id' =>$logist_id[0]['logist'],
            'column_name' =>'v_rabote',
            'order_id' =>$order_id,
        ]);
        broadcast(new UpdateVRaboteHeaderEvent($logist_id[0]['logist']))->toOthers();
        return response()->json([
            'status' => 'success',
            'message' =>'Заявка успешно сохранена в назначение ставки',
        ], 200);
    }
    public function get_logist_list()
    {
        $list_users= User::whereRelation('roles', 'role_id', '=', 2)->get();
        foreach ($list_users as $user) {
            foreach ($user->getAttributes() as $key => $value) {
                if ($value === null) {
                    $user->{$key} = '';
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'list_users' => $list_users
        ], 201);
    }
    public function add_new_adres_pogruzka(Request $request)
    {
        $grade_id=$request->input('grade_id');
        $id_ts=$request->input('id_ts');
        $pogruzka_or_vygruzka=$request->input('pogruzka_or_vygruzka');
        $TS_list_pogruzka = GradePogruzka::
              where('grade_id', $grade_id)
            ->where('id_ts', $id_ts)
            ->where('pogruzka_or_vygruzka', $pogruzka_or_vygruzka)
            ->orderBy('id_pogruzka', 'DESC')
            ->get('id_pogruzka');
        if($TS_list_pogruzka->isEmpty())
        {
            $id_pogruzka=0;
            //если новый адрес погрузки или выгрузки
            GradePogruzka::create([
                'grade_id' => $grade_id,
                'id_ts' =>$id_ts,
                'pogruzka_or_vygruzka' =>$pogruzka_or_vygruzka,
                'id_pogruzka' =>$id_pogruzka,
                'adres_pogruzki' => '',
                'date_ts' => '',
                'time_ts' => '',
            ]);
        }
        else
        {
            $id_pogruzka=$TS_list_pogruzka[0]['id_pogruzka']+1;
            //если уже есть то берём самый большой id погрузки выгрузки и добавляем к нему
            GradePogruzka::create([
                'grade_id' => $grade_id,
                'id_ts' =>$id_ts,
                'pogruzka_or_vygruzka' =>$pogruzka_or_vygruzka,
                'id_pogruzka' =>$TS_list_pogruzka[0]['id_pogruzka']+1,
                'adres_pogruzki' => '',
                'date_ts' => '',
                'time_ts' => '',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Погрузка/выгрузка успешно добавлена',
            'id_pogruzka' =>$id_pogruzka,
        ], 200);
    }
    public function add_summ(Request $request)
    {
        $grade_id=$request->input('grade_id');
        $id_ts=$request->input('id_ts');
        $id_summa=$request->input('id_summa');
        $sum=GradeSumma::create([
            'grade_id' => $grade_id,
            'id_ts' =>$id_ts,
            'id_summa' =>$id_summa,
            'summa' =>0,
            'data' =>''
        ]);
        return response()->json([
            'status' => 'success',
            'message' =>'Сумма добавлена',
            'id' =>$sum['id'],
        ], 200);
    }
    public function deleteSumm()
    {
        $this->gradeSummaModel->delById();
        return response()->json([
            'status' => 'success',
            'message' =>'Сумма удалена',
        ], 200);
    }
    public function add_pogruzka_grade(Request $request)
    {
        $arr_to_DB=$request->input('arr_to_DB');

        foreach($arr_to_DB as $req){

            GradePogruzka::create([
                'grade_id' => $req['grade_id'],
                'id_ts' => $req['id_ts'],
                'pogruzka_or_vygruzka' => $req['pogruzka_or_vygruzka'],
                'adres_pogruzki' => $req['adres_pogruzki'],
                'id_pogruzka' => $req['id_pogruzka'],
                'date_ts' => '',
                'time_ts' => '',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Погрузка/выгрузка успешно сохранена',
        ], 200);


    }
    public function update_final_grade(Request $request)
    {
        $grade_id=$request->input('grade_id');
        $id_ts=$request->input('id_ts');
        $vid_TS=$request->input('vid_TS');
        $perevozchik=$request->input('perevozchik');
        $voditel=$request->input('voditel');
        $nomer_TS=$request->input('nomer_TS');
        $nomer_PP=$request->input('nomer_PP');
        $kol_gruz_TS=$request->input('kol_gruz_TS');
        $rasstojanie_TS=$request->input('rasstojanie_TS');
        $ob_summa=$request->input('ob_summa');
        $NDS_check=$request->input('NDS_check');
        $predoplata=$request->input('predoplata');
//        $podpisannaja_name_doc=$request->input('podpisannaja_name_doc');
//        $podpisannaja_path_doc=$request->input('podpisannaja_path_doc');
//        $schet_factura_name_doc=$request->input('schet_factura_name_doc');
//        $schet_factura_path_doc=$request->input('schet_factura_path_doc');
        $TN_check=$request->input('TN_check');
        $checked2=$request->input('checked2');
        $terminal_TS=$request->input('terminal_TS');
        FinalGrade::updateOrCreate(
            //массив что ищем
            [
                'grade_id' => $grade_id,
                'id_ts' => $id_ts,
            ],
            //массив что апдейтим
            [
                'grade_id' => $grade_id,
                'id_ts' => $id_ts,
                'vid_TS' => $vid_TS,
                'perevozchik'=>$perevozchik,
                'voditel'=>$voditel,
                'nomer_TS'=>$nomer_TS,
                'nomer_PP'=>$nomer_PP,
                'kol_gruz_TS'=>$kol_gruz_TS,
                'rasstojanie_TS'=>$rasstojanie_TS,
                'ob_summa'=>$ob_summa,
                'NDS_check'=>$NDS_check,
                'predoplata'=>$predoplata,
//                'podpisannaja_name_doc'=>$podpisannaja_name_doc,
//                'podpisannaja_path_doc'=>$podpisannaja_path_doc,
//                'schet_factura_name_doc'=>$schet_factura_name_doc,
//                'schet_factura_path_doc'=>$schet_factura_path_doc,
                'TN_check'=>$TN_check,
                'checked2'=>$checked2,
                'terminal_TS'=>$terminal_TS
            ]);
    }
    public function update_one_data(Request $request)
    {
        $elem_arr = $request->input('elem');
        $name = $request->input('name');
        $grade_id = $request->input('grade_id');

        $grade_list = FinalGrade::
                where('grade_id', '=', $grade_id)
            -> where('id_ts', '=', $elem_arr['id_ts'])
            ->update(
                [
                    $name => $elem_arr[$name]
                ],
            );
        return $grade_list;
    }
    public function update_one_data_summa(Request $request)
    {
        $elem_arr = $request->input('elem');
        $name = $request->input('name');
        $id_summa = $request->input('id_summa');
        $grade_id = $request->input('grade_id');
        $data_to_up = $request->input('data_to_up');
        $grade_list = GradeSumma::
               where('grade_id', '=', $grade_id)
            -> where('id_ts', '=', $elem_arr['id_ts'])
            -> where('id_summa', '=', $id_summa)
            ->update(
                [
                    $name => $data_to_up
                ],
            );
    }
    public function update_one_data_pogruzka(Request $request)
    {
        $elem_arr = $request->input('elem');
        $name = $request->input('name');
        $id_pogr = $request->input('id_pogr');
        $pogr_or_vygr = $request->input('pogr_or_vygr');
        $data_to_up = $request->input('data_to_up');
        $grade_id = $request->input('grade_id');

        $grade_list = GradePogruzka::
               where('grade_id', '=', $grade_id)
            -> where('id_ts', '=', $elem_arr['id_ts'])
            -> where('id_pogruzka', '=', $id_pogr)
            -> where('pogruzka_or_vygruzka', '=', $pogr_or_vygr)
            ->update(
                [
                    $name => $data_to_up
                ],
            );
    }
    //метод обновляющий данные из инпута
    public function up_in_db_gruzootpravitel(Request $request)
    {
        $grade_list = GradePogruzka::
        where('grade_id', '=', $request->input('grade_id'))
            -> where('id_ts', '=', $request->input('id_ts'))
            -> where('id_pogruzka', '=', $request->input('id_pogr'))
            -> where('pogruzka_or_vygruzka', '=', $request->input('pogr_or_vygr'))
            ->update(
                [
                    'adres_pogruzki' => $request->input('inp_pog_id')
                ],
            );
    }
    public function update_one_gruzzootpravitel_from_select(Request $request)
    {
        $elem_arr = $request->input('elem');
        $name = $request->input('name');
        $id_pogr = $request->input('id_pogr');
        $pogr_or_vygr = $request->input('pogr_or_vygr');
        $data_to_up = $request->input('data_to_up');
        $grade_id = $request->input('grade_id');

        $grade_list = GradePogruzka::
            where('grade_id', '=', $grade_id)
            -> where('id_ts', '=', $elem_arr['id_ts'])
            -> where('id_pogruzka', '=', $id_pogr)
            -> where('pogruzka_or_vygruzka', '=', $pogr_or_vygr)
            ->update(
                [
                    $name => $data_to_up
                ],
            );
    }
    public function deletePogVygInTS()
    {
        //удаляем файлы погрузки
        $this->docService->deleteOneGradeDoc(request('grade_id'),request('id_ts'),request('id_pogruzka'),request('id_doc_type'));

        //удаляем запись о погрузке
      $id=$this->gradePogruzkaModel->deleteOnePogrVygr(request('grade_id'),request('id_ts'),request('id_pogruzka'),request('pogruzka_or_vygruzka'));
        return response()->json([
            'status' => 'success',
            'message' =>'Погрузка/выгрузка успешно удалена',
            'id' =>$id,
        ], 200);
    }

    public function delete_file_grade(Request $request)
    {
        $grade_id = $request->input('grade_id');
        $id_ts = $request->input('id_ts');
        $id_pogruzka = $request->input('id_pogruzka');
        $id_doc_type = $request->input('id_doc_type');
        $grade_path = GradeDocuments::
        where('grade_id', '=', $grade_id)
            -> where('id_ts', '=', $id_ts)
            -> where('id_pogruzka', '=', $id_pogruzka)
            -> where('id_doc_type', '=', $id_doc_type)
            ->get();

        if (!$grade_path->isEmpty()) {
            try {
                $path_to_del = public_path() . "/grade_doc/" . $grade_path[0]['path_doc'];
                unlink($path_to_del);
                $grade_path = GradeDocuments::
                where('grade_id', '=', $grade_id)
                    -> where('id_ts', '=', $id_ts)
                    -> where('id_pogruzka', '=', $id_pogruzka)
                    -> where('id_doc_type', '=', $id_doc_type)
                    ->delete();
            }
            catch (\Throwable $e)
            {

            }
        }
        return response()->json([
            'status' => 'success',
            'message' =>'Файл успешно удалён',
            'id_doc_type' =>$id_doc_type,
        ], 200);
    }
    public function download_all_doc_grade($grade_id,$id_ts)
    {
        $zipFileName = 'AllDocuments.zip';

        try {
            $path_to_del = public_path("/images/zip/".$zipFileName);
            unlink($path_to_del);
        }
        catch (\Throwable $e)
        {

        }

        $search_in=['3','4','5'];
        $files = GradeDocuments::
        where('grade_id', '=', $grade_id)
            -> where('id_ts', '=', $id_ts)
            -> where('id_pogruzka', '=', 0)
            -> whereIn('id_doc_type', $search_in)
            ->get();

        $zip = new ZipArchive;

        if ($zip->open(public_path("/images/zip/".$zipFileName), ZipArchive::CREATE) === TRUE) {
            // Add Multiple file
            foreach($files as $file) {
                $zip->addFile( public_path() . "/grade_doc/" . $file['path_doc'], public_path() . "/grade_doc/" . $file['name_doc']);
            }
            $zip->close();
        }
        $filetopath=public_path("/images/zip/".$zipFileName);
        // Create Download Response
        if(file_exists($filetopath)){
            return response()->download($filetopath);
        }
    }
    public function deleteTSFromRightColumn()
    {
        $elem=request('TS');

        //удалим все файлы связанные с этим ТС
        $this->docService->deleteGradeDoc(request('grade_id'),$elem['id_ts']);
        //удалим погрузки связанные с этим ТС
        $this->gradePogruzkaModel->delPogruzka(request('grade_id'),$elem['id_ts']);
        //удалим сумму
        $this->gradeSummaModel->delSumma(request('grade_id'),$elem['id_ts']);
        //удалим из основной таблицы значения
        $this->finalGradeModel->delGrade(request('grade_id'),$elem['id_ts']);

        return response()->json([
            'status' => 'success',
            'message' =>'Grade успешно удалён',
        ], 200);

    }
    public function deleteStavka()
    {
        foreach (request('ids') as $stavka)
        {
            //получим grade_id id_ts
            $grade=$this->finalGradeModel->getGradeIdTs($stavka);
            //удалим все файлы связанные с этим ТС
            $this->docService->deleteGradeDoc($grade[0]['grade_id'],$grade[0]['id_ts']);
            //удалим погрузки связанные с этим ТС
            $this->gradePogruzkaModel->delPogruzka($grade[0]['grade_id'],$grade[0]['id_ts']);
            //удалим сумму
            $this->gradeSummaModel->delSumma($grade[0]['grade_id'],$grade[0]['id_ts']);
            //удалим из основной таблицы значения
            $this->finalGradeModel->delGrade($grade[0]['grade_id'],$grade[0]['id_ts']);
        }


        return response()->json([
            'status' => 'success',
            'message' =>'Grade успешно удалён',
        ], 200);

    }
    public function get_final_grades_data(Request $request)
    {
        $grade_id = $request->input('grade_id');
        $grade_list = FinalGrade::where('grade_id', '=', $grade_id) ->get();

        foreach ($grade_list as $grade)
        {
            $summa = GradeSumma::where('grade_id', '=', $grade_id)->where('id_ts', '=', $grade['id_ts'])->get();
            foreach ($summa as $one_summ)
            {
                $one_summ['show_DP_date']=false;
            }
            $grade['summa_list']=$summa;
            $TS_list_pogruzka = GradePogruzka::where('grade_id', '=', $grade_id)->where('pogruzka_or_vygruzka', '1')->where('id_ts', $grade['id_ts'])->get();
            $TS_list_vygruzka = GradePogruzka::where('grade_id', '=', $grade_id)->where('pogruzka_or_vygruzka', '2')->where('id_ts', $grade['id_ts'])->get();

            //добавляем название к типу ТС
            if(($grade['vid_TS']=='')||($grade['vid_TS']==null))
            {
                $grade['vid_TSNazvanie']='';
            }
            else
            {
                //получаем само название в инпут для показа
                $vid_TSNazvanie=$this->vidTSModel->getTsNameBYId($grade['vid_TS']);
                $grade['vid_TSNazvanie']=$vid_TSNazvanie;
            }

            //получаем название перевозчика
            if(($grade['perevozchik']=='')||($grade['perevozchik']==null))
            {
                $grade['perevozchik_TSNazvanie']='';
            }
            else
            {
                $vid_TSNazvanie=$this->perevozkaModel->getPerevozkaNameBYId($grade['perevozchik']);
                $grade['perevozchik_TSNazvanie']=$vid_TSNazvanie;
            }

            //получаем название водителя
            if(($grade['voditel']=='')||($grade['voditel']==null))
            {
                $grade['voditel_TSNazvanie']='';
            }
            else
            {
                $vid_TSNazvanie=$this->voditelModel->getVoditelNameBYId($grade['voditel']);
                $grade['voditel_TSNazvanie']=$vid_TSNazvanie;
            }

            //получаем название (номер) ТС
            if(($grade['nomer_TS']=='')||($grade['nomer_TS']==null))
            {
                $grade['TS_TSNazvanie']='';
            }
            else
            {
                $TS_TSNazvanie=$this->TSModalModel->getTSModalNameBYId($grade['nomer_TS']);
                $grade['TS_TSNazvanie']=$TS_TSNazvanie;
            }
            //получаем номер ПП
            if(($grade['nomer_PP']=='')||($grade['nomer_PP']==null))
            {
                $grade['PP_Nazvanie']='';
            }
            else
            {
                $PP_Nazvanie=$this->PPModel->getPPNameBYId($grade['nomer_PP']);
                $grade['PP_Nazvanie']=$PP_Nazvanie;
            }

            foreach ($TS_list_pogruzka as $pogruzka)
            {

                $name_doc = GradeDocuments::
                where('grade_id', '=', $grade_id)
                    ->where('id_ts', '=', $grade['id_ts'])
                    ->where('id_doc_type', '=',1)
                    ->where('id_pogruzka', '=', $pogruzka['id_pogruzka'])
                    ->get();

                if ($name_doc->isEmpty()) {
                    $pogruzka['doc_name']='';
                }
                else
                {
                    $pogruzka['doc_name']=$name_doc[0]['name_doc'];
                }
                $pogruzka['show_DP_date']=false;
                $pogruzka['show_DP_time']=false;

                if(($pogruzka['adres_pogruzki']=='')||($pogruzka['adres_pogruzki']==null))
                {
                    $pogruzka['adres_pogruzki_show']='';
                }
                else
                {

                    //получаем изначальный адрес в инпут для показа

                    $adres_pogruzke_show=$this->gruzootpravitelAdresService->getOneAdresForSearch($pogruzka['adres_pogruzki']);
                 //   $adres_pogruzke_show = Gruzootpravitel::where('id', '=', $pogruzka['adres_pogruzki']) ->get();
                 //   $adres_pogruzke_show = $adres_pogruzke_show[0]['nazvanie'];
                    $pogruzka['adres_pogruzki_show']=$adres_pogruzke_show;
                }

            }

            foreach ($TS_list_vygruzka as $pogruzka)
            {
                $name_doc = GradeDocuments::
                where('grade_id', '=', $grade_id)
                    ->where('id_ts', '=', $grade['id_ts'])
                    ->where('id_doc_type', '=',2)
                    ->where('id_pogruzka', '=', $pogruzka['id_pogruzka'])
                    ->get();
                if ($name_doc->isEmpty()) {
                    $pogruzka['doc_name']='';
                }
                else
                {
                    $pogruzka['doc_name']=$name_doc[0]['name_doc'];
                }
                $pogruzka['show_DP_date']=false;
                $pogruzka['show_DP_time']=false;

                if(($pogruzka['adres_pogruzki']=='')||($pogruzka['adres_pogruzki']==null))
                {
                    $pogruzka['adres_vygruzki_show']='';
                }
                else
                {
                    $adres_pogruzke_show=$this->gruzootpravitelAdresService->getOneAdresForSearch($pogruzka['adres_pogruzki']);
                   // $adres_pogruzke_show = Gruzootpravitel::where('id', '=', $pogruzka['adres_pogruzki']) ->get();
                   // $adres_pogruzke_show = $adres_pogruzke_show[0]['nazvanie'];
                    $pogruzka['adres_vygruzki_show']=$adres_pogruzke_show;
                }

            }
            $grade['adres_pogruzki_TS']=$TS_list_pogruzka;
            $grade['adres_vygr_TS']=$TS_list_vygruzka;

            $name_doc = GradeDocuments::
            where('grade_id', '=', $grade_id)
                ->where('id_ts', '=', $grade['id_ts'])
                ->where('id_doc_type', '=',3)
                ->get();
            if ($name_doc->isEmpty()) {
                $grade['podpisannaya_doc_name']='';
            }
            else
            {
                $grade['podpisannaya_doc_name']=$name_doc[0]['name_doc'];
            }
            $name_doc = GradeDocuments::
            where('grade_id', '=', $grade_id)
                ->where('id_ts', '=', $grade['id_ts'])
                ->where('id_doc_type', '=',4)
                ->get();
            if ($name_doc->isEmpty()) {
                $grade['schet_doc_name']='';
            }
            else
            {
                $grade['schet_doc_name']=$name_doc[0]['name_doc'];
            }
            $name_doc = GradeDocuments::
            where('grade_id', '=', $grade_id)
                ->where('id_ts', '=', $grade['id_ts'])
                ->where('id_doc_type', '=',5)
                ->get();
            if ($name_doc->isEmpty()) {
                $grade['faktura_doc_name']='';
            }
            else
            {
                $grade['faktura_doc_name']=$name_doc[0]['name_doc'];
            }
            $name_doc = GradeDocuments::
            where('grade_id', '=', $grade_id)
                ->where('id_ts', '=', $grade['id_ts'])
                ->where('id_doc_type', '=',6)
                ->get();
            if ($name_doc->isEmpty()) {
                $grade['TN_doc_name']='';
            }
            else
            {
                $grade['TN_doc_name']=$name_doc[0]['name_doc'];
            }


        }


        return response()->json([
            'status' => 'success',
            'message' =>'Заявка успешно получена',
            'grade_list' =>$grade_list,
        ], 200);

    }
    public function get_start_data_grade(Request $request)
    {
        $id = $request->input('order_id');
        $one_order = Orders::where('id', '=', $id) ->get();
        if ($one_order->isEmpty())
        {
            return response()->json([
                'status' => 'error',
                'message' =>'Нет такой заявки',
            ], 422);
        }
        else
        {
            $TS_list= TS::where('order_id', '=', $id)->get();
            foreach ($TS_list as $ts)
            {
                //  return dd($ts['id_ts']);
                $TS_list_pogruzka = PogruzkaTS::where('order_id', '=', $id)->where('pogruzka_or_vygruzka', '1')->where('id_ts', $ts['id_ts'])->get();
                $TS_list_vygruzka = PogruzkaTS::where('order_id', '=', $id)->where('pogruzka_or_vygruzka', '2')->where('id_ts', $ts['id_ts'])->get();

                $ts['adres_pogruzki_TS']=$TS_list_pogruzka;
                $ts['adres_vygr_TS']=$TS_list_vygruzka;

                //получаем само название в инпут для показа
                if(($ts['vid_TS']=='')||($ts['vid_TS']==null))
                {
                    $ts['vid_TSNazvanie']='';
                }
                else
                {
                    $vid_TSNazvanie=$this->vidTSModel->getTsNameBYId($ts['vid_TS']);
                    $ts['vid_TSNazvanie']=$vid_TSNazvanie;
                }

                //получаем название перевозчика
                if(($ts['perevozchik']=='')||($ts['perevozchik']==null))
                {
                    $ts['perevozchik_TSNazvanie']='';
                }
                else
                {
                    $vid_TSNazvanie=$this->perevozkaModel->getPerevozkaNameBYId($ts['perevozchik']);
                    $ts['perevozchik_TSNazvanie']=$vid_TSNazvanie;
                }

                //получаем название водителя
                if(($ts['voditel']=='')||($ts['voditel']==null))
                {
                    $ts['voditel_TSNazvanie']='';
                }
                else
                {
                    $vid_TSNazvanie=$this->voditelModel->getVoditelNameBYId($ts['voditel']);
                    $ts['voditel_TSNazvanie']=$vid_TSNazvanie;
                }
                //получаем название номер ТС
                if(($ts['nomer_TS']=='')||($ts['nomer_TS']==null))
                {
                    $ts['TS_TSNazvanie']='';
                }
                else
                {
                    $TS_TSNazvanie=$this->TSModal->getTSModalNameBYId($ts['nomer_TS']);
                    $ts['TS_TSNazvanie']=$TS_TSNazvanie;
                }
                //получаем название ПП
                if(($ts['nomer_PP']=='')||($ts['nomer_PP']==null))
                {
                    $ts['PP_Nazvanie']='';
                }
                else
                {
                    $PP_Nazvanie=$this->PPModel->getPPNameBYId($ts['nomer_PP']);
                    $ts['PP_Nazvanie']=$PP_Nazvanie;
                }



                foreach ($TS_list_pogruzka as $ts1)
                {
                    $ts1['date_ts']='';
                    $ts1['time_ts']='';
                   if(($ts1['adres_pogruzki']=='')||($ts1['adres_pogruzki']==null))
                    {
                       $ts1['adres_pogruzki_show']='';
                   }
                    else
                    {
                        $adres_pogruzke_show=$this->gruzootpravitelAdresService->getOneAdresForSearch($ts1['adres_pogruzki']);
                        $ts1['adres_pogruzki_show']=$adres_pogruzke_show;
                    }



                }
                foreach ($TS_list_vygruzka as $ts1)
                {
                    $ts1['date_ts']='';
                    $ts1['time_ts']='';

                    if(($ts1['adres_pogruzki']=='')||($ts1['adres_pogruzki']==null))
                    {
                        $ts1['adres_vygruzki_show']='';
                    }
                    else
                    {
                        $adres_pogruzke_show=$this->gruzootpravitelAdresService->getOneAdresForSearch($ts1['adres_pogruzki']);
                        $ts1['adres_vygruzki_show']=$adres_pogruzke_show;
                    }

                }

            }

            return response()->json([
                'status' => 'success',
                'message' =>'Заявка успешно получена',
                'data' =>$one_order,
                'TS_list' =>$TS_list
            ], 200);
        }

    }
    public function store_grade_file(Request $request)
    {
        $path=time().'_'.$request['file_name'].'.'.$request['extension'];
        $doc_in_db=GradeDocuments::
        where('grade_id','=',$request['grade_id'])
            -> where('id_ts','=',$request['id_ts'])
            -> where('id_pogruzka','=',$request['id_pogruzka'])
            -> where('id_doc_type','=',$request['id_doc_type'])
            ->get();
        if (!$doc_in_db->isEmpty()) {
            try {
                $path_to_del = public_path() . "/grade_doc/" . $doc_in_db[0]['path_doc'];
                unlink($path_to_del);
            }
            catch (\Throwable $e)
            {

            }
        }
        GradeDocuments::updateOrCreate(
        //массив что ищем
            [
                'grade_id' => $request['grade_id'],
                'id_ts' => $request['id_ts'],
                'id_pogruzka' => $request['id_pogruzka'],
                'id_doc_type' => $request['id_doc_type'],
            ],
            //массив что апдейтим
            [
                'grade_id' => $request['grade_id'],
                'id_ts' => $request['id_ts'],
                'id_pogruzka' => $request['id_pogruzka'],
                'id_doc_type' => $request['id_doc_type'],
                'name_doc' => $request['full_name'],
                'path_doc' => $path,

            ]);
        $request['file']->move(public_path('/grade_doc/'), $path);
        return response()->json([
            'status' => 'success',
            'message' =>'Файл grade успешно сохранён',
            'name_doc' =>$request['full_name'],
            'id_doc_type' =>$request['id_doc_type'],
        ], 200);
    }
    public function get_ob_budzet_down(Request $request)
    {
        $id = $request->input('order_id');
        $TS_list= TS::where('order_id', '=', $id)->get();
        if ($TS_list->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' =>'Файл grade успешно посчитан',
                'count' =>0,
            ], 200);
        }
        else
        {
            $count=0;
            foreach ($TS_list as $ts)
            {
                $count=$count+$ts['stavka_TS'];
            }
            return response()->json([
                'status' => 'success',
                'message' =>'Файл grade успешно посчитан',
                'count' =>$count,
            ], 200);
        }
    }
    public function docsSetting()
    {
        return view('front.docs');
    }
    //универсальный метод удаления, подходит для водителей,ТС,ПП
    public function deleteFromSettings()
    {
        //удалим сами файлы
        $this->docService->deleteDocFromDBUn();
        //проапдейтим колонку с поисковым запросом в таблице FinalGrades
        $this->finalGradeModel->upFinalGradeToNull();
        //удалим сам запрос из нужной таблицы
        $this->searchService->deleteMainSearch();
    }
    public function addPerevozchikToStavka()
    {
        //проапдейтим колонку перевозчика
        $this->finalGradeModel->upFinalGradePerevozchik();
        return response()->json([
            'status' => 'success',
            'message' =>'Перевозчик успешно обновлён',
        ], 200);
    }
}
