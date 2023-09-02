<?php

namespace App\Services;

use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelAdresa;
use App\Models\GruzootpravitelBank;
use App\Models\GruzootpravitelContact;
use App\Models\GruzootpravitelFile;
use App\Models\Perevozka;
use App\Models\PerevozkaBanks;
use App\Models\PerevozkaContacts;
use App\Models\PerevozkaFiles;
use App\Services\GruzootpravitelAdresService;


class PerevozchikService
{
    protected $gruzootpravitel;
    protected $perevozkaModel;
    protected $perevozkaContacsModel;
    protected $perevozkaBanksModel;
    protected $perevozkaFilesModel;
    protected $gruzootpravitelContact;
    protected $gruzootpravitelAdresa;
    protected $gruzootpravitelBank;
    protected $gruzootpravitelFile;
    protected $gruzootpravitelAdresService;


    public function __construct(Gruzootpravitel $gruzootpravitel, GruzootpravitelContact $gruzootpravitelContact, GruzootpravitelAdresa $gruzootpravitelAdresa, GruzootpravitelBank $gruzootpravitelBank,
                                GruzootpravitelFile $gruzootpravitelFile, Perevozka $perevozkaModel, PerevozkaContacts $perevozkaContacsModel, PerevozkaBanks $perevozkaBanksModel, PerevozkaFiles $perevozkaFilesModel)
    {
        $this->gruzootpravitel = $gruzootpravitel;
        $this->gruzootpravitelContact = $gruzootpravitelContact;
        $this->gruzootpravitelAdresa = $gruzootpravitelAdresa;
        $this->gruzootpravitelBank = $gruzootpravitelBank;
        $this->gruzootpravitelFile = $gruzootpravitelFile;
        $this->perevozkaModel = $perevozkaModel;
        $this->perevozkaContacsModel = $perevozkaContacsModel;
        $this->perevozkaBanksModel = $perevozkaBanksModel;
        $this->perevozkaFilesModel = $perevozkaFilesModel;
//        $this->gruzootpravitelAdresService = $gruzootpravitelAdresService;
    }

    public function deleteContact($gruzootpravitel_id)
    {
        $gruzootpravitelContactModel = new GruzootpravitelContact;
        $gruzootpravitelContactModel->deleteContactMod($gruzootpravitel_id);
    }
    //сохраняем нового перевозчика
    public function saveNewPerevozchik($forma,$nazvanie,$data_registracii,$telefon,$INN,$OGRN,$email,$generalnii_direktor,$telefon_gen_dir,$email_gen_dir,$yridicheskii_adres,$pochtovyi_adres,$kontakty,$bank_arr,$doc_files,$gorod_bazirovania)
    {
        //сохраняем перевозчика
        $main=$this->perevozkaModel->savePerevozkaModel($forma,$nazvanie,$data_registracii,$telefon,$INN,$OGRN,$email,$generalnii_direktor,$telefon_gen_dir,$email_gen_dir,$yridicheskii_adres,$pochtovyi_adres,$gorod_bazirovania);
        //получаем id перевозчика
        $perevozkaId=$main['id'];
//        //создаём новые контакты адреса и банки
        foreach ($kontakty as $kontakt) {
            $this->perevozkaContacsModel->saveContact($kontakt,$perevozkaId);
        }

        foreach ($bank_arr as $bank)
        {
            $this->perevozkaBanksModel->saveBank($bank,$perevozkaId);
        }
        foreach ($doc_files as $file)
        {
            $this->perevozkaFilesModel->saveFile($file,$perevozkaId);
        }
        return $perevozkaId;
    }
    //обновляем перевозчика
    public function updatePerevozchik($perevozchikId,$forma,$nazvanie,$data_registracii,$telefon,$INN,$OGRN,$email,$generalnii_direktor,$telefon_gen_dir,$email_gen_dir,$yridicheskii_adres,$pochtovyi_adres,$kontakty,$bank_arr,$doc_files,$gorod_bazirovania)
    {
        $this->perevozkaModel->updatePerevozchik($perevozchikId,$forma,$nazvanie,$data_registracii,$telefon,$INN,$OGRN,$email,$generalnii_direktor,$telefon_gen_dir,$email_gen_dir,$yridicheskii_adres,$pochtovyi_adres,$gorod_bazirovania);

       // $this->perevozkaModel->updateAdresa($perevozchikId,request('adresa'),request('forma'),request('nazvanie'));


    }
    //меняем данные у грузоотправителя
    public function updateGruzootpravitel($gruzootpravitelId,$forma,$nazvanie,$data_registracii,$INN,$OGRN,$telefon,$email,$generalnii_direktor,$telefon_gen_dir,$yridicheskii_adres,$pochtovyi_adres,$kontakty,$adresa,$bank_arr,$doc_files)
    {
        $this->gruzootpravitel->updateGruzootpravitel($gruzootpravitelId,$forma,$nazvanie,$data_registracii,$INN,$OGRN,$telefon,$email,$generalnii_direktor,$telefon_gen_dir,$yridicheskii_adres,$pochtovyi_adres);
        //обновим контакты и банки
        GruzootpravitelContact::where('gruzootpravitel_id', '=',$gruzootpravitelId)->delete();
        GruzootpravitelBank::where('gruzootpravitel_id', '=',$gruzootpravitelId)->delete();
        foreach ($kontakty as $kontakt) {
            $this->gruzootpravitelContact->saveContact($kontakt,$gruzootpravitelId);
        }
        foreach ($bank_arr as $bank)
        {
            $this->gruzootpravitelBank->saveBank($bank,$gruzootpravitelId);
        }

        $this->gruzootpravitelFile->updateOldFiles($doc_files,$gruzootpravitelId);

    }
    public function deletePerevozkaFiles($id)
    {
        $old_docs=$this->perevozkaFilesModel->getFilesList($id);
        foreach ($old_docs as $old_doc)
        {
            try {
                $path_to_del = public_path() . "/modal/" . $old_doc['server_name'];
                unlink($path_to_del);
                $this->gruzootpravitelFile->deleteFilesFromDB($old_doc['id']);
            }
            catch (\Throwable $e)
            {

            }
        }
    }

}
