<?php

namespace App\Http\Controllers;

use App\Models\FinalGrade;
use App\Models\VidTS;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    protected $searchService;
    protected $vidTS;
    protected $finalGrade;

    public function __construct(
        SearchService $searchService,
        VidTS $vidTS,
        FinalGrade $finalGrade
    )
    {
        $this->searchService = $searchService;
        $this->vidTS = $vidTS;
        $this->finalGrade = $finalGrade;
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
        foreach ($res as $oneRes)
        {
            $temp=$this->finalGrade->getIdBYColumnId('vid_TS',$oneRes['id']);
            if(!$temp->isEmpty())
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
//        if(request('searchWord')=='')
//        {
//            return response()->json([
//                'status' => 'success',
//                'message' =>'результаты поиска получены',
//                'res' =>'',
//                'count' =>0,
//            ], 200);
//        }
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
