<?php

namespace App\Services;

use App\Models\FinalGrade;
use App\Models\GradePogruzka;
use App\Models\GruzootpravitelAdresa;
use App\Models\GruzootpravitelContact;
use App\Models\Perevozka;
use App\Models\TS;
use App\Models\VidTS;

class StavkiService
{
    protected $TS;
    protected $finalGradeModel;
    protected $gruzootpravitelAdresService;
    protected $vidTSModel;
    protected $perevozkaModel;
    protected $gruzootpravitelAdresaModel;

    public function __construct(TS $TS, FinalGrade $finalGradeModel, GruzootpravitelAdresService $gruzootpravitelAdresService, VidTS $vidTS,Perevozka $perevozkaModel, GruzootpravitelAdresa $gruzootpravitelAdresaModel)
    {
        $this->TS=$TS;
        $this->finalGradeModel=$finalGradeModel;
        $this->GruzootpravitelAdresService=$gruzootpravitelAdresService;
        $this->vidTSModel = $vidTS;
        $this->perevozkaModel = $perevozkaModel;
        $this->gruzootpravitelAdresaModel = $gruzootpravitelAdresaModel;
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
    public function getStavkiList($main_second,$oldstavki)
    {
        if($main_second=='main')
        {
            $grade_list=$this->finalGradeModel->getStavkiList();
            return dd('1');
        }
        if($main_second=='second')
        {
            //переделать метод под grade которые мы получили ранее из поиска и вызывать метод поиска из search service
            $grade_list=$this->finalGradeModel->getGradeListByIds($oldstavki);
            return dd('2');
        }
        foreach ($grade_list as $grade)
        {
            //получаем список погрузок и выгрузок у данного ТС
            $TS_list_pogruzka = GradePogruzka::where('grade_id', $grade['grade_id'])->where('pogruzka_or_vygruzka', '1')->where('id_ts', $grade['id_ts'])->get();
            $TS_list_vygruzka = GradePogruzka::where('grade_id', $grade['grade_id'])->where('pogruzka_or_vygruzka', '2')->where('id_ts', $grade['id_ts'])->get();
            //получаем имена погрузок и выгрузок
            foreach ($TS_list_pogruzka as $pogruzka)
            {
                if(($pogruzka['adres_pogruzki']=='')||($pogruzka['adres_pogruzki']==null))
                {
                    $pogruzka['adres_pogruzki_show']='';
                }
                else
                {
                    //получаем изначальный адрес в инпут для показа
                    $adres_pogruzke_show=$this->gruzootpravitelAdresaModel->getAdresById($pogruzka['adres_pogruzki']);
                    $pogruzka['adres_pogruzki_show']=$adres_pogruzke_show;
                }

            }
            foreach ($TS_list_vygruzka as $pogruzka)
            {
                if(($pogruzka['adres_pogruzki']=='')||($pogruzka['adres_pogruzki']==null))
                {
                    $pogruzka['adres_vygruzki_show']='';
                }
                else
                {
                    $adres_pogruzke_show=$this->gruzootpravitelAdresaModel->getAdresById($pogruzka['adres_pogruzki']);
                    $pogruzka['adres_vygruzki_show']=$adres_pogruzke_show;
                }
            }
            $grade['adres_pogruzki_TS']=$TS_list_pogruzka;
            $grade['adres_vygr_TS']=$TS_list_vygruzka;
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
            //ставим значение НДС
            if($grade['NDS_check']==0)
            {
                $grade['NDS_check']="Без НДС";
            }
            if($grade['NDS_check']==1)
            {
                $grade['NDS_check']="С НДС";
            }

        }

        return $grade_list;

    }
}
