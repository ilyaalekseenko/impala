<?php

namespace App\Services;

use App\Models\FinalGrade;
use App\Models\GradePogruzka;
use App\Models\GruzootpravitelAdresa;
use App\Models\GruzootpravitelContact;
use App\Models\Perevozka;
use App\Models\TS;
use App\Models\VidTS;
use Illuminate\Support\Collection;

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
    public function getSearchResultsmanager($searchWord,$model,$fieldTosearch,$searchOffset,$zakachikId)
    {
        $model = 'App\Models\\' . $model;
        return $model::
        where($fieldTosearch, 'like', '%'.$searchWord.'%')
        ->where('gruzootpravitel_id',$zakachikId)
            ->offset($searchOffset)
            ->limit(10)
            ->get();
    }
    public function getSearchResultsAvtor($searchWord,$model,$fieldTosearch,$searchOffset)
    {
        $model = 'App\Models\\' . $model;
        $data= $model::
        where($fieldTosearch, 'like', '%'.$searchWord.'%')
            ->offset($searchOffset)
            ->limit(10)
            ->orderBy('gruzootpravitel_id')
            ->leftJoin('gruzootpravitels', 'gruzootpravitel_adresas.gruzootpravitel_id', '=', 'gruzootpravitels.id')
            ->select('gruzootpravitel_adresas.*', 'gruzootpravitels.forma_id','gruzootpravitels.nazvanie as nazvGruz')
            ->get();

        foreach ($data as $value) {
          if($value['forma_id']===null)
          {
              $value['forma_id']='';
          }
          if($value['nazvGruz']===null)
          {
              $value['nazvGruz']='';
          }
        }
        return $data;
    }
    public function searchBackStavkiInput($searchWord,$model,$fieldTosearch,$searchOffset,$fieldToSearchFinalGrade)
    {
        //если фильтруем по вид ТС или по перевозчику
        if($model=='Perevozka')
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
                    $oneRes['ts_name']=$oneRes['nazvanie'];
            }

            if(request('tipTSParentId')!='')
            {
                //оставим в массиве $res только те значения которые совпадают с выбранным типом ТС
                foreach ($res as  $key =>$oneRes)
                {
                    $existFinalGrade=$this->finalGrade->checkIfExistTipTS($oneRes['id'],request('tipTSParentId'));
                    if(!$existFinalGrade)
                    {
                        $res->forget($key);
                    }
                }
            }
            if(request('otkudaParentId')!='')
            {
                $otkuda=$this->otkudaKudaPer(request('otkudaParentId'),1);
                foreach ($res as  $key =>$oneRes)
                {
                    //perevozchik grade id_ts в final_grade должны совпасть
                    $existFinalGrade=$this->finalGrade->checkIfExistPer($oneRes['id'],$otkuda);
                    if(!$existFinalGrade)
                    {
                        $res->forget($key);
                    }
                }

            }
            if(request('kudaParentId')!='')
            {
                $otkuda=$this->otkudaKudaPer(request('kudaParentId'),2);
                foreach ($res as  $key =>$oneRes)
                {
                    //perevozchik grade id_ts в final_grade должны совпасть
                    $existFinalGrade=$this->finalGrade->checkIfExistPer($oneRes['id'],$otkuda);
                    if(!$existFinalGrade)
                    {
                        $res->forget($key);
                    }
                }

            }

        }
        //фильтруем по откуда и куда
        if($model=='GruzootpravitelAdresa')
        {

            $res=[];
            $res=$this->fromToAdres($searchWord,request('pogrVygrInp'),request('otkudaParentId'),request('kudaParentId'));
                foreach ($res as  $key =>$oneRes)
                {
                  //  echo ($oneRes);
                    //перевозчик
                    if(request('perevozchikParentId')!='')
                    {
                        //perevozchik 79  id_ts 20 grade_id 226
                        //perevozchik grade id_ts в final_grade должны совпасть
                        $existFinalGrade=$this->finalGrade->checkIfExistPerOne(request('perevozchikParentId'),$oneRes,'perevozchik');
                        if(!$existFinalGrade)
                        {
                            $res->forget($key);
                        }

                    }
                    //вид ТС
                    if(request('tipTSParentId')!='')
                    {
                        $existFinalGradeVidTS=$this->finalGrade->checkIfExistPerOne(request('tipTSParentId'),$oneRes,'vid_TS');
                        if(!$existFinalGradeVidTS)
                        {
                            $res->forget($key);
                        }
                    }
                }


            //оставляем только уникальные элементы коллекции
            $res = $res->groupBy('ts_name')->map(function ($group) {
                return $group->first();
            })->values();
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
    public function fromToAdres($from,$pogrVygr,$otkuda,$kuda)
    {
        //получаем все адреса погрузки выгрузки по названию
        $adresaId=$this->gruzootpravitelAdresa->getAdresByName($from);

        $newCollectionAdresa = collect();
        $testArr=[];
        //проверяем строки из таблицы grade_pogruzkas везде где наши id совпадают с колонкой adres_pogruzki
       $counter=0;
       //получаем не искомое поле откуда или куда

        if($pogrVygr==1)
        {
            $searchField=$kuda;
        }
        else
        {
            $searchField=$otkuda;
        }
        if($searchField!='')
        {
            //получили все результаты не искомого поля
            $rowPogruzkaSecond=$this->gradePogruzka->getSecondPogruzka($pogrVygr,$otkuda,$kuda);
        }
        else
        {
            $rowPogruzkaSecond='';
        }

        foreach ($adresaId as $key =>$oneAdres)
        {

            $rowPogruzka=$this->gradePogruzka->getRowByPogruzka($oneAdres['id'],$pogrVygr);

            if($rowPogruzka->isEmpty())
            {
                $adresaId->forget($key);
            }
            else
            {

                //добавить отдельный адрес ведь их несолько может быть
                foreach ($rowPogruzka as $row)
                {
                    $counter++;
//тут сравнение со вторым полем откуда иои куда чтобы искать только то что выбрано

                    $flag=false;
                    if($rowPogruzkaSecond=='')
                    {
                        $flag=true;
                    }
                    else
                    {
                        foreach ($rowPogruzkaSecond as $second)
                        {
                            if(($second['grade_id']==$row['grade_id'])&&($second['id_ts']==$row['id_ts']))
                            {
                                $flag=true;
                            }
                        }
                    }


                    if($flag)
                    {
                        $oneAdres['ts_name']=$oneAdres['adres'];
                        $oneAdres['grade_id']=$row['grade_id'];
                        $oneAdres['id_ts']=$row['id_ts'];



                        array_push($testArr,
                            array('ts_name'=> $oneAdres['adres'],'id_ts'=> $row['id_ts'],'grade_id'=> $row['grade_id'],'id'=> $oneAdres['id'],'nazvanie'=> $oneAdres['nazvanie'])
                        );
                    }

                    //адрес есть но в итогов пропадает, вернее заменяется на оидакновый.
                    //например перевозчик tyman, адрес2 id_ts должно быть 20 а его нету

                }
            }

        }
       // $testArr=array_unique($testArr, SORT_REGULAR);

        $collection = new Collection($testArr);
        return $collection;
       // return dd($adresaId);
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

    public function otkudaKudaPer($idPogrVygr,$pogrVygr)
    {
        $rowOtkuda=$this->gradePogruzka->getRowByPogruzka($idPogrVygr,$pogrVygr);
        //из таблицы final_grades получаем соответствующие vid_TS
        $rowOtkuda=$rowOtkuda->toArray();
        return $rowOtkuda;
    }

    public function otkudaKuda($idPogrVygr,$pogrVygr)
    {
        //получаем все строки в колонке погрузка где есть наше id_pogruzka и забираем оттуда grade_id
        //получается весь список транспортных средст на которых есть в колонке откуда
        $rowOtkuda=$this->gradePogruzka->getRowByPogruzka($idPogrVygr,$pogrVygr);
        //из таблицы final_grades получаем соответствующие vid_TS
        $rowOtkuda=$rowOtkuda->toArray();
        //нужно получить только те Vid_TS где и Grade и Id_ts совпадают с нашими
        $tempOtkuda=[];
        foreach($rowOtkuda as $onegrade)
        {
            $vidTSFromFinalGrade=$this->finalGrade->getVidTsByGradeAndTs($onegrade);
            $vidTSFromFinalGrade=$vidTSFromFinalGrade->toArray();
            //сформируем массив
            $tempOtkuda[]=$vidTSFromFinalGrade;
        }
        // Результирующий массив
        $resultOtkudaArray = [];
        // Перебор внешнего массива
        foreach ($tempOtkuda as $subArray) {
            // Перебор внутреннего массива
            foreach ($subArray as $element) {
                // Добавление элемента в результирующий массив
                $resultOtkudaArray[] = $element;
            }
        }
        return $resultOtkudaArray;
    }


}
