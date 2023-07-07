<?php

namespace App\Http\Controllers;

use App\Models\FinalGrade;
use App\Models\GradePogruzka;
use App\Models\VidTS;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{

    protected $searchService;
    protected $vidTS;
    protected $finalGrade;
    protected $gradePogruzka;

    public function __construct(
        SearchService $searchService,
        VidTS $vidTS,
        FinalGrade $finalGrade,
        GradePogruzka $gradePogruzka,
    )
    {
        $this->searchService = $searchService;
        $this->vidTS = $vidTS;
        $this->finalGrade = $finalGrade;
        $this->gradePogruzka=$gradePogruzka;
    }

    //метод поиска на бэке TS
    public function searchBackTS(Request $request)
    {
        //получаем результаты поиска
        //$res=$this->searchService->getSearchResults(request('searchWord'),request('model'),request('fieldToSearch'),request('searchOffset'));
        $res=$this->vidTS->getVidTsGlobal(request('searchOffset'));
//        $finalRes=[];
//        foreach ($res as $oneRes)
//        {
//            $temp=$this->finalGrade->getIdBYColumnId('vid_TS',$oneRes['id']);
//            if(!$temp->isEmpty())
//            {
//                array_push($finalRes,$oneRes);
//            }
//        }
        return response()->json([
            'status' => 'success',
            'message' =>'результаты поиска получены',
            'res' =>$res,
        ], 200);
    }
    //метод поиска на бэке TS
    public function searchBackTSStavki(Request $request)
    {
        //получаем результаты поиска
        $res=$this->vidTS->getVidTsGlobalStavki();
        $finalRes=[];
        //подготовка к проверке откуда
        if(request('otkudaParentId')!='')
        {
            $resultOtkudaArray=$this->searchService->otkudaKuda(request('otkudaParentId'),1);
        }
        //подготовка к проверке куда
        if(request('kudaParentId')!='')
        {
            $resultKudaArray=$this->searchService->otkudaKuda(request('kudaParentId'),2);
        }
        foreach ($res as $oneRes)
        {

            //флаг проверки добавлять ли в результирующий массив
            $nullFlag=true;
            $temp=$this->finalGrade->getIdBYColumnId('vid_TS',$oneRes['id']);
            if($temp->isEmpty())
            {
                $nullFlag=false;
            }
            //смотрим есть ли у этого конкретного Типа ТС перевозчик из списка
            if(request('perevozchikParentId')!='')
            {
                $issetPerevozchik=$this->finalGrade->getIssetVidTs($oneRes['id'],request('perevozchikParentId'));
                if($issetPerevozchik->isEmpty())
                {
                    $nullFlag=false;
                }
            }
            //смотрим есть ли у этого конкретного Типа ТС откуда из списка
            if(request('otkudaParentId')!='')
            {
                if (!in_array($oneRes['id'], $resultOtkudaArray)) {
                    $nullFlag=false;
                }
            }
            //смотрим есть ли у этого конкретного Типа ТС откуда из списка
            if(request('kudaParentId')!='')
            {
                if (!in_array($oneRes['id'], $resultKudaArray)) {
                    $nullFlag=false;
                }
            }
            //если все проверки пройдены то добавляем в результирующий массив
            if($nullFlag)
            {
                array_push($finalRes,$oneRes);
            }


        }
        return response()->json([
            'status' => 'success',
            'message' =>'результаты поиска получены',
            'res' =>$finalRes,
        ], 200);
    }
    //метод поиска на бэке
    public function searchBack(Request $request)
    {
        //получаем результаты поиска
      $res=$this->searchService->getSearchResults(request('searchWord'),request('model'),request('fieldToSearch'),request('searchOffset'));
        return response()->json([
            'status' => 'success',
            'message' =>'результаты поиска получены',
            'res' =>$res,
        ], 200);
    }
    //метод поиска на бэке
    public function searchBackStavkiInput(Request $request)
    {
        //получаем результаты поиска
        $res=$this->searchService->searchBackStavkiInput(request('searchWord'),request('model'),request('fieldToSearch'),request('searchOffset'),request('fieldToSearchFinalGrade'));
        $count=$res->count();
        return response()->json([
            'status' => 'success',
            'message' =>'результаты поиска получены',
            'res' =>$res,
            'count' =>$count,
        ], 200);
    }
    //метод поиска на бэке связанный с компанией
    public function searchBackKompaniya(Request $request)
    {
        //получаем результаты поиска
        $res=$this->searchService->getSearchResultsKompaniya(request('searchWord'),request('model'),request('fieldToSearch'),request('searchOffset'),request('kompaniya'));
        return response()->json([
            'status' => 'success',
            'message' =>'результаты поиска получены',
            'res' =>$res,
        ], 200);
    }

    public function searchBackStavki()
    {
        $stavkiList=$this->searchService->getSearchResultsStavki();
        return response()->json([
            'status' => 'success',
            'message' =>'Список ставок успешно получен',
            'stavkiList' =>$stavkiList[0],
            'tipesCount' =>$stavkiList[1],
        ], 200);
    }

}
