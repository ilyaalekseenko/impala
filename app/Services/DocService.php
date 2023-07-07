<?php

namespace App\Services;

use App\Models\DocsTemplate;
use App\Models\GradeDocuments;
use App\Models\GruzootpravitelContact;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
//        Storage::move($sourcePath, $destinationPath);
//        Image::make($from_req)->save(public_path($to).$name);
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
    public function deleteOneGradeDoc($grade_id,$id_ts,$id_pogruzka,$id_doc_type)
    {
        $grade_path = GradeDocuments::
        where('grade_id', '=', $grade_id)
            -> where('id_ts', '=', $id_ts)
            -> where('id_pogruzka', '=', $id_pogruzka)
            -> where('id_doc_type', '=', $id_doc_type)
            ->get();

        if (!$grade_path->isEmpty()) {
            try {
                $path_to_del = public_path() . "/grade_doc/" . $grade_path[0]['path_doc'];
                unlink($path_to_del);
                $grade_path = GradeDocuments::
                where('grade_id', '=', $grade_id)
                    -> where('id_ts', '=', $id_ts)
                    -> where('id_pogruzka', '=', $id_pogruzka)
                    -> where('id_doc_type', '=', $id_doc_type)
                    ->delete();
            }
            catch (\Throwable $e)
            {

            }
        }
    }
    public function deleteDocFromDBUn()
    {
        $filesModel=request('filesModel');
        $filesModel = 'App\Models\\' . $filesModel;
        $getFilesNames=$filesModel::where(request('searchColumn'),request('id'))->get();
        foreach ($getFilesNames as $old_doc)
        {
            try {
                $path_to_del = public_path() . "/modal/" . $old_doc['server_name'];
                unlink($path_to_del);
                $filesModel::where('id',$old_doc['id'])->delete();
            }
            catch (\Throwable $e)
            {

            }
        }
    }


}
