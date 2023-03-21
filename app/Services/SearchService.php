<?php

namespace App\Services;

use App\Models\GruzootpravitelContact;
use App\Models\TS;

class SearchService
{
    protected $TS;

    public function __construct(TS $TS)
    {
        $this->TS=$TS;
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
}
