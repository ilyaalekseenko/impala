<?php

namespace App\Http\Controllers;

use App\Models\FinalGrade;
use App\Models\GruzootpravitelAdresa;
use App\Services\StavkiService;
use Illuminate\Http\Request;

class StavkiController extends Controller
{

    protected $stavkiService;
    protected $gruzootpravitelAdresaModel;
    protected $finalGradesModel;

    public function __construct(StavkiService $stavkiService, GruzootpravitelAdresa $gruzootpravitelAdresaModel, FinalGrade $finalGradesModel)
    {
        $this->stavkiService=$stavkiService;
        $this->gruzootpravitelAdresaModel = $gruzootpravitelAdresaModel;
        $this->finalGradesModel = $finalGradesModel;

    }

    public function stavki(Request $request)
    {
        return view('front.stavki')->with('auth_user',  auth()->user());
    }
    public function getStavkiList()
    {
        $stavkiList=$this->stavkiService->getStavkiList('main','');
        $tipesCount=$this->finalGradesModel->countGrades();
        return response()->json([
            'status' => 'success',
            'message' =>'Список ставок успешно получен',
            'tipesCount' =>$tipesCount,
            'stavkiList' =>$stavkiList,
        ], 200);
    }
}
