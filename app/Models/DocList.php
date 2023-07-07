<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocList extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getPorNomer($docType)
    {
       // $porNomer=DocList::where('type',$docType)->pluck('por_nomer');
        $porNomer=DocList::where('type',$docType)->max('por_nomer');
        if($porNomer==null)
        {
            $porNomer=1;
        }
        else
        {
            $porNomer++;
        }
            return $porNomer;
    }

    public function upPorNomer($doc_type,$porNomer,$fileName)
    {
             DocList::create([
            'por_nomer' => $porNomer,
            'type' => $doc_type,
            'file_name' => $fileName,
        ]);
    }
}
