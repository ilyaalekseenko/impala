<?php

namespace App\Services;

use App\Models\FinalGrade;
use App\Models\GradePogruzka;
use App\Models\GruzootpravitelAdresa;
use App\Models\GruzootpravitelContact;
use App\Models\Perevozka;
use App\Models\TS;
use App\Models\VidTS;

class SearchService
{
    protected $TS;
    protected $vidTS;
    protected $perevozka;
    protected $gruzootpravitelAdresa;
    protected $gradePogruzka;
    protected $finalGrade;
    protected $stavkiService;

    public function __construct(
        TS $TS,
        VidTS $vidTS,
        Perevozka $perevozka,
        GruzootpravitelAdresa $gruzootpravitelAdresa,
        GradePogruzka $gradePogruzka,
        FinalGrade $finalGrade,
        StavkiService $stavkiService

    )
    {
        $this->TS=$TS;
        $this->vidTS=$vidTS;
        $this->perevozka=$perevozka;
        $this->gruzootpravitelAdresa=$gruzootpravitelAdresa;
        $this->gradePogruzka=$gradePogruzka;
        $this->finalGrade=$finalGrade;
        $this->stavkiService=$stavkiService;
    }

    public function getSearchResults($searchWord,$model,$fieldTosearch,$searchOffset)
    {
        $model = 'App\Models\\' . $model;
        return $model::
            where($fieldTosearch, 'like', '%'.$searchWord.'%')
            ->offset($searchOffset)
            ->limit(10)
            ->get();
    }
    public function searchBackStavkiInput($searchWord,$model,$fieldTosearch,$searchOffset,$fieldToSearchFinalGrade)
    {
        //если фильтруем по вид ТС или по перевозчику
        if(($model=='VidTS')||($model=='Perevozka'))
        {
            $modelIn = 'App\Models\\' . $model;
            $res=$modelIn::
                when($searchWord!='', function($q) use ($fieldTosearch,$searchWord) {
                    return $q->where($fieldTosearch, 'like', '%' . $searchWord . '%');
                        })
                ->get();

            foreach ($res as  $key =>$oneRes)
            {
                //проверяем существует ли такая запись в FinalGrade
                $existFinalGrade=$this->finalGrade->checkIfExist($oneRes['id'],$fieldToSearchFinalGrade);
                if(!$existFinalGrade)
                {
                    $res->forget($key);
                }
                if($model=='Perevozka')
                {
                    $oneRes['ts_name']=$oneRes['nazvanie'];
                }
            }
        }
        if($model=='GruzootpravitelAdresa')
        {
            $res=[];
            $res=$this->fromToAdres($searchWord,request('pogrVygrInp'));
        }
        //в отфильтрованном результате забираем 10 нужных нам записей
        $slicedItems = $res->splice($searchOffset, 10);
        return $slicedItems;
    }
    public function getSearchResultsKompaniya($searchWord,$model,$fieldTosearch,$searchOffset,$kompaniya)
    {

        $model = 'App\Models\\' . $model;
        return $model::
        where($fieldTosearch, 'like', '%'.$searchWord.'%')
            ->where('kompaniya',$kompaniya)
            ->offset($searchOffset)
            ->limit(10)
            ->get();
    }
    public function deleteMainSearch()
    {
        $mainModel=request('mainModel');
        $mainModel = 'App\Models\\' . $mainModel;
        $mainModel::where('id',request('id'))->delete();
    }
    public function getSearchResultsStavki()
    {
        //получаем все записи где ТИП ТС совпадает с поиском
        //получаем все записи Final Grade где id ТИП ТС нужное
        //Делаем тоже самое для всех остальных поисков
        //находим общее значение

        $mainSearchArr=[];
        $mainSearchArrTipTS=[];
        $mainSearchArrPerevozchik=[];
        $mainSearchArrOtkuda=[];
        $mainSearchArrKuda=[];
        //vid_TS
        if(request('tipTS')!='')
        {
            $mainSearchArrTipTS=$this->oneSearch('vid_TS');
        }
        //перевозчик
        if(request('perevozchik')!='')
        {
            $mainSearchArrPerevozchik=$this->oneSearch('perevozchik');
        }
        //адрес откуда
        if(request('otkuda')!='')
        {
            $mainSearchArrOtkuda=$this->fromTo(request('otkuda'),'1');
        }
        //адрес куда
        if(request('kuda')!='')
        {
            $mainSearchArrKuda=$this->fromTo(request('kuda'),'2');
        }
        // Проверяем, если два или более массива не пустые
        $nonEmptyArrays = array_filter([$mainSearchArrTipTS, $mainSearchArrPerevozchik, $mainSearchArrOtkuda, $mainSearchArrKuda], function ($array) {
            return !empty($array);
        });

        if (count($nonEmptyArrays) >= 2) {
            // Инициализируем результирующий массив
                foreach ($nonEmptyArrays as $poluResAr)
                {
                    // Перебираем значения первого массива
                    foreach ($poluResAr as $value) {
                        // Проверяем, присутствуют ли значения в остальных массивах
                        $foundInAllArrays = true;

                        foreach ($nonEmptyArrays as $array) {
                            if (!in_array($value, $array)) {
                                $foundInAllArrays = false;
                                break;
                            }
                        }
                        // Если значение присутствует во всех не пустых массивах, добавляем его в результирующий массив
                        if ($foundInAllArrays) {
                            $mainSearchArr[] = $value;
                        }
                    }
                }
        }
        else
        {
            $mainSearchArr = reset($nonEmptyArrays);
        }
        //считаем сколько всего значений в поиске
        $count=count($mainSearchArr);
        //отступы
        $mainSearchArr = array_slice($mainSearchArr, request('offset'), request('limit'));
        //получим сами строки
        $stavkiList=$this->stavkiService->getStavkiList('second',$mainSearchArr);
        return [$stavkiList,$count];
    }
    public function oneSearch($type)
    {
        $mainSearchArr=[];
        if($type=='vid_TS')
        {
            $listIds=$this->vidTS->getIDByNameSearch();
        }
        if($type=='perevozchik')
        {
            $listIds=$this->perevozka->getIDByNameSearch();
        }
        $tipTs=[];
        //получаем строку final_grade по vid_TS
        foreach ($listIds as $one)
        {
            $temp=$this->finalGrade->getIdBYColumnId($type,$one['id']);
            if(!$temp->isEmpty())
            {
                array_push($tipTs,$temp);
            }
        }
        if($tipTs)
        {
            foreach ($tipTs[0] as $oneId)
            {
                array_push($mainSearchArr,$oneId['id']);
            }
        }
        return $mainSearchArr;
    }
    public function fromToAdres($from,$pogrVygr)
    {
        //получаем все адреса погрузки выгрузки по названию
        $adresaId=$this->gruzootpravitelAdresa->getAdresByName($from);
        //проверяем строки из таблицы grade_pogruzkas везде где наши id совпадают с колонкой adres_pogruzki
        foreach ($adresaId as $key =>$oneAdres)
        {
            $rowPogruzka=$this->gradePogruzka->getRowByPogruzka($oneAdres['id'],$pogrVygr);
            if($rowPogruzka->isEmpty())
            {
                $adresaId->forget($key);
            }
            $oneAdres['ts_name']=$oneAdres['adres'];
        }
        //получить строки где совпадают id
        return $adresaId;
    }
    public function fromTo($from,$pogrVygr)
    {
        $mainSearchArr=[];
        $adresaId=$this->gruzootpravitelAdresa->getAdresByName($from);
        //получаем строки из таблицы grade_pogruzkas везде где наши id совпадают с колонкой adres_pogruzki
        $gradePogruzkaArr=[];
        foreach ($adresaId as $oneAdres)
        {
            $rowPogruzka=$this->gradePogruzka->getRowByPogruzka($oneAdres['id'],$pogrVygr);
            if(!$rowPogruzka->isEmpty())
            {
                array_push($gradePogruzkaArr,$rowPogruzka);
            }

        }
        //в таблице final_grades получаем id по grade_id id_ts
        foreach ($gradePogruzkaArr as $oneGrade)
        {
            foreach ($oneGrade as $oneGradeIns)
            {
                $finalGrade=$this->finalGrade->getIdByGrade($oneGradeIns['grade_id'],$oneGradeIns['id_ts']);
                array_push($mainSearchArr,$finalGrade[0]['id']);
            }
        }
        return $mainSearchArr;
    }

}
