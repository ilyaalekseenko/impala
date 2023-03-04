<?php

namespace App\Services;

use App\Models\GruzootpravitelContact;

class GruzootpravitelService
{
    public function deleteContact($gruzootpravitel_id)
    {
        $gruzootpravitelContactModel = new GruzootpravitelContact;
        $gruzootpravitelContactModel->deleteContactMod($gruzootpravitel_id);
    }
}
