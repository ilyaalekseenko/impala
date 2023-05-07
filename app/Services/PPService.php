<?php

namespace App\Services;

use App\Models\PP;
use App\Models\PPFiles;
use App\Models\TSModal;
use App\Models\TSModalFiles;
use Illuminate\Http\Request;


class PPService
{
    protected $PPModel;
    protected $PPFilesModel;

    public function __construct(PP $PPModel, PPFiles $PPFilesModel)
    {
        $this->PPModel = $PPModel;
        $this->PPFilesModel = $PPFilesModel;
    }

    //сохраняем нового перевозчика
    public function saveNewPP($request,$doc_files)
    {
        //сохраняем перевозчика
        $main=$this->PPModel->savePPModel($request);
        //получаем id перевозчика
        $voditelId=$main['id'];

        foreach ($doc_files as $file)
        {
            $this->PPFilesModel->saveFile($file,$voditelId);
        }
        return $voditelId;
    }
    //обновляем перевозчика
    public function updatePP($voditelId,$marka,$nomer,$dlina,$tonn,$nomer_documenta,$kompaniya)
    {
        $this->PPModel->updatePP($voditelId,$marka,$nomer,$dlina,$tonn,$nomer_documenta,$kompaniya);
    }

}
