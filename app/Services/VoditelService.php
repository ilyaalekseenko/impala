<?php

namespace App\Services;

use App\Models\Voditel;
use App\Models\VoditelFiles;


class VoditelService
{
    protected $voditelModel;
    protected $voditelFilesModel;

    public function __construct(Voditel $voditelModel, VoditelFiles $voditelFilesModel)
    {
        $this->voditelModel = $voditelModel;
        $this->voditelFilesModel = $voditelFilesModel;
    }

    //сохраняем нового перевозчика
    public function saveNewVoditel($FIO,$telefon,$dataRozdeniya,$seriyaPassporta,$kemVydan,$kogdaVydan,$propiska,$kompaniya,$doc_files,$nomer_vod_ud)
    {
        //сохраняем перевозчика
        $main=$this->voditelModel->saveVoditelModel($FIO,$telefon,$dataRozdeniya,$seriyaPassporta,$kemVydan,$kogdaVydan,$propiska,$kompaniya,$nomer_vod_ud);
        //получаем id перевозчика
        $voditelId=$main['id'];

        foreach ($doc_files as $file)
        {
            $this->voditelFilesModel->saveFile($file,$voditelId);
        }
        return $voditelId;
    }
    //обновляем перевозчика
    public function updateVoditel($voditelId,$FIO,$telefon,$dataRozdeniya,$seriyaPassporta,$kemVydan,$kogdaVydan,$propiska,$kompaniya,$nomer_vod_ud)
    {
        $this->voditelModel->updateVoditel($voditelId,$FIO,$telefon,$dataRozdeniya,$seriyaPassporta,$kemVydan,$kogdaVydan,$propiska,$kompaniya,$nomer_vod_ud);
    }
//    //меняем данные у грузоотправителя
//    public function updateGruzootpravitel($gruzootpravitelId,$forma,$nazvanie,$data_registracii,$INN,$OGRN,$telefon,$email,$generalnii_direktor,$telefon_gen_dir,$yridicheskii_adres,$pochtovyi_adres,$kontakty,$adresa,$bank_arr,$doc_files)
//    {
//        $this->gruzootpravitel->updateGruzootpravitel($gruzootpravitelId,$forma,$nazvanie,$data_registracii,$INN,$OGRN,$telefon,$email,$generalnii_direktor,$telefon_gen_dir,$yridicheskii_adres,$pochtovyi_adres);
//        //обновим контакты и банки
//        GruzootpravitelContact::where('gruzootpravitel_id', '=',$gruzootpravitelId)->delete();
//        GruzootpravitelBank::where('gruzootpravitel_id', '=',$gruzootpravitelId)->delete();
//        foreach ($kontakty as $kontakt) {
//            $this->gruzootpravitelContact->saveContact($kontakt,$gruzootpravitelId);
//        }
//        foreach ($bank_arr as $bank)
//        {
//            $this->gruzootpravitelBank->saveBank($bank,$gruzootpravitelId);
//        }
//
//        $this->gruzootpravitelFile->updateOldFiles($doc_files,$gruzootpravitelId);
//
//    }
//    public function deletePerevozkaFiles($id)
//    {
//        $old_docs=$this->perevozkaFilesModel->getFilesList($id);
//        foreach ($old_docs as $old_doc)
//        {
//            try {
//                $path_to_del = public_path() . "/modal/" . $old_doc['server_name'];
//                unlink($path_to_del);
//                $this->gruzootpravitelFile->deleteFilesFromDB($old_doc['id']);
//            }
//            catch (\Throwable $e)
//            {
//
//            }
//        }
//    }

}
