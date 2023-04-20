<?php

namespace App\Services;

use App\Models\DocsTemplate;
use App\Models\GradeDocuments;
use App\Models\GruzootpravitelContact;

class DocService
{

    protected $docsTemplate;
    protected $gradeDocumentsModel;
    public function __construct(DocsTemplate $docsTemplate, GradeDocuments $gradeDocumentsModel)
    {
        $this->docsTemplate = $docsTemplate;
        $this->gradeDocumentsModel = $gradeDocumentsModel;
    }

    //откуда берём ( например из запроса), куда ложим и имя с которым сохраняем
    public function storeDoc($from_req, $to, $name)
    {
        $from_req->move(public_path($to), $name);
    }
    public function delDoc($path_to_del)
    {
        try {
            unlink($path_to_del);
        }
        catch (\Throwable $e)
        {

        }
    }
    public function deleteGradeDoc($grade_id,$id_ts)
    {
        for($i=3;$i<=5;$i++) {
            $grade_path = $this->gradeDocumentsModel->getDoc($grade_id, $id_ts, 0, $i);
            if (!$grade_path->isEmpty()) {
                try {
                    $path_to_del = public_path() . "/grade_doc/" . $grade_path[0]['path_doc'];
                    unlink($path_to_del);
                    $this->gradeDocumentsModel->delDoc($grade_id, $id_ts, 0, $i);
                } catch (\Throwable $e) {
                }
            }
        }
    }


}
