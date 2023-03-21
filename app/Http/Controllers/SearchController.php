<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    protected $searchService;

    public function __construct(
        SearchService $searchService,
    )
    {
        $this->searchService = $searchService;
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
}
