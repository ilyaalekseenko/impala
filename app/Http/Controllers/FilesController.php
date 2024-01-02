<?php

namespace App\Http\Controllers;

use App\Models\DocsTemplate;
use App\Services\DocService;
use Illuminate\Http\Request;

class FilesController extends Controller
{

    private $docsTemplMod;
    private $docService;

    public function __construct
    (
        DocsTemplate $docsTemplMod,
        DocService $docService,
    )
    {
        $this->docsTemplMod = $docsTemplMod;
        $this->docService = $docService;
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
}
