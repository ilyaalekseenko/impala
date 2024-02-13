<?php

namespace App\Http\Controllers;

use App\Models\DocsTemplate;
use App\Models\GradeDocuments;
use App\Services\DocService;
use Illuminate\Http\Request;

class FilesController extends Controller
{

    private $docsTemplMod;
    private $docService;
    private $gradeDocuments;

    public function __construct
    (
        DocsTemplate $docsTemplMod,
        DocService $docService,
        GradeDocuments $gradeDocuments
    )
    {
        $this->docsTemplMod = $docsTemplMod;
        $this->docService = $docService;
        $this->gradeDocuments = $gradeDocuments;
    }

    public function delFile()
    {
        try {
            $path_to_del = public_path() . request('pathToDel');
            unlink($path_to_del);
        }
        catch (\Throwable $e)
        {

        }
    }
    public function store_doc_templ_nom(Request $request)
    {
        //в docs_templates создать или проапдейтить переменную с типом nom
//        DocsTemplate::updateOrInsert(
//            ['doc_type' => 'NOM'],
//            ['doc_name' => '3','doc_type' => 'NOM']
//        );
        $isset = $this->docsTemplMod->getFirstInModel('doc_type',request('doc_type'));
        if ($isset !== null) {
            //удаляем документ
            $this->docService->delDoc(public_path() . "/united/" . $isset['doc_name']);
            //обновляем
            $this->docsTemplMod->updateOneFieldInModel($isset['id'],'doc_name',request('full_name'));
        }
        else{
            $this->docsTemplMod->createDocFieldsInModel(request('doc_type'),request('full_name'));
        }
        //сохраняем
        $this->docService->storeDoc(request('file_xlsx'),'/united/',request('full_name'));
        return response()->json([
            'status' => 'success',
            'message' =>'Файл успешно сохранён',
        ], 200);
    }
    public function store_grade_file_mult()
    {

      //  $filesData=request('files_data');
        $filesData = json_decode(request('files_data'), true);

        $createdDocsInfo=[];
        foreach ($filesData as $key=> $fileData)
        {
            $path=time().'_'.$key.'_'.$fileData['file_name'].'.'.$fileData['extension'];
           $oneDoc= $this->gradeDocuments->createDoc(request('grade_id'),request('id_ts'),request('id_pogruzka'),request('id_doc_type'),$fileData['full_name'],$path);
            $createdDocsInfo[]=$oneDoc;
           //переместим файл по ключу
            request('files')[$key]->move(public_path('/grade_doc/'), $path);
        }
        $createdDocsInfo=$this->gradeDocuments->getDoc(request('grade_id'),request('id_ts'),request('id_pogruzka'),request('id_doc_type'));
      //  $request['file']->move(public_path('/grade_doc/'), $path);
        return response()->json([
            'status' => 'success',
            'message' =>'Файл grade успешно сохранён',
            'createdDocsInfo' =>$createdDocsInfo,
            'id_doc_type' =>request('id_doc_type'),
        ], 200);

    }
}
