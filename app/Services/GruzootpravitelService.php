<?php

namespace App\Services;

use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelAdresa;
use App\Models\GruzootpravitelBank;
use App\Models\GruzootpravitelContact;
use App\Models\GruzootpravitelFile;
use GruzootpravitelAdresService;


class GruzootpravitelService
{
    protected $gruzootpravitel;
    protected $gruzootpravitelContact;
    protected $gruzootpravitelAdresa;
    protected $gruzootpravitelBank;
    protected $gruzootpravitelFile;
    protected $gruzootpravitelAdresService;

    public function __construct(Gruzootpravitel $gruzootpravitel, GruzootpravitelContact $gruzootpravitelContact, GruzootpravitelAdresa $gruzootpravitelAdresa, GruzootpravitelBank $gruzootpravitelBank,
                                GruzootpravitelFile $gruzootpravitelFile)
    {
        $this->gruzootpravitel = $gruzootpravitel;
        $this->gruzootpravitelContact = $gruzootpravitelContact;
        $this->gruzootpravitelAdresa = $gruzootpravitelAdresa;
        $this->gruzootpravitelBank = $gruzootpravitelBank;
        $this->gruzootpravitelFile = $gruzootpravitelFile;
//        $this->gruzootpravitelAdresService = $gruzootpravitelAdresService;
    }

    public function deleteContact($gruzootpravitel_id)
    {
        $gruzootpravitelContactModel = new GruzootpravitelContact;
        $gruzootpravitelContactModel->deleteContactMod($gruzootpravitel_id);
    }
    //сохраняем нового грузоотправителя
    public function saveNewGruzootpravitel($forma,$nazvanie,$data_registracii,$INN,$OGRN,$telefon,$email,$generalnii_direktor,$telefon_gen_dir,$yridicheskii_adres,$pochtovyi_adres,$kontakty,$adresa,$bank_arr,$doc_files)
    {
        //сохраняем грузоотправителя
        $gruz_main=$this->gruzootpravitel->saveGruzootpravitelModel($forma,$nazvanie,$data_registracii,$INN,$OGRN,$telefon,$email,$generalnii_direktor,$telefon_gen_dir,$yridicheskii_adres,$pochtovyi_adres);
        //получаем id грузоотправителя
        $gruzootpravitelId=$gruz_main['id'];
        //создаём новые контакты адреса и банки
        foreach ($kontakty as $kontakt) {
            $this->gruzootpravitelContact->saveContact($kontakt,$gruzootpravitelId);
        }
        foreach ($adresa as $adres)
        {
           $this->gruzootpravitelAdresa->saveAdres($adres,$gruzootpravitelId,$forma,$nazvanie);
        }
        foreach ($bank_arr as $bank)
        {
            $this->gruzootpravitelBank->saveBank($bank,$gruzootpravitelId);
        }
        foreach ($doc_files as $file)
        {
            $this->gruzootpravitelFile->saveFile($file,$gruzootpravitelId);
        }
        return $gruzootpravitelId;
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

}
