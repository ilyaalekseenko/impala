<?php

namespace App\Services;

use App\Models\TSModal;
use App\Models\TSModalFiles;


class TSModalService
{
    protected $TSModalModel;
    protected $TSModalFilesModel;

    public function __construct(TSModal $TSModalModel, TSModalFiles $TSModalFilesModel)
    {
        $this->TSModalModel = $TSModalModel;
        $this->TSModalFilesModel = $TSModalFilesModel;
    }

    //сохраняем нового перевозчика
    public function saveNewTSModal($tip,$marka,$nomer,$tonn,$nomer_documenta,$kompaniya,$doc_files)
    {
        //сохраняем перевозчика
        $main=$this->TSModalModel->saveTSModalModel($tip,$marka,$nomer,$tonn,$nomer_documenta,$kompaniya);
        //получаем id перевозчика
        $voditelId=$main['id'];

        foreach ($doc_files as $file)
        {
            $this->TSModalFilesModel->saveFile($file,$voditelId);
        }
        return $voditelId;
    }
    //обновляем перевозчика
    public function updateTSModal($voditelId,$tip,$marka,$nomer,$tonn,$nomer_documenta,$kompaniya)
    {
        $this->TSModalModel->updateTSModal($voditelId,$tip,$marka,$nomer,$tonn,$nomer_documenta,$kompaniya);
    }

}
