<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruzootpravitelFile extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function saveFile($file,$gruzootpravitelId)
    {

            if($file['doc_path']!='')
            {
                GruzootpravitelFile::create([
                    'gruzootpravitel_id' => $gruzootpravitelId,
                    'file_name' => $file['file_name'].'.'.$file['ext'],
                    'server_name' => $file['doc_path'],
                ]);
            }

    }
    public function updateOldFiles($doc_files,$gruzootpravitelId)
    {

        if($doc_files)
        {
            //удаляем все старые документы которых нет в массиве приходящего с фронта
            $old_docs=GruzootpravitelFile::where('gruzootpravitel_id', $gruzootpravitelId)->get();
            //старый массив в БД который
            foreach ($old_docs as $old_doc)
            {
                $flag=false;
                //приходящий массив
                foreach ($doc_files as $doc)
                {
                    if($doc['id']!=null)
                    {
                        if($old_doc['id']==$doc['id'])
                        {
                            $flag=true;
                        }
                    }
                }

                //если нету в новом массиве тогда удаляем и файл и из БД
                if($flag==false)
                {
                    try {
                        $path_to_del = public_path() . "/modal/" . $old_doc['server_name'];
                        unlink($path_to_del);
                        GruzootpravitelFile::where('id', '=',$old_doc['id'])->delete();
                    }
                    catch (\Throwable $e)
                    {

                    }
                }
            }
            foreach ($doc_files as $doc)
            {
                //если новый док и не пустой
                if(($doc['id']==null)&&($doc['doc_path']!=null))
                {
                    GruzootpravitelFile::create([
                        'gruzootpravitel_id' => $gruzootpravitelId,
                        'file_name' => $doc['file_name'].'.'.$doc['ext'],
                        'server_name' => $doc['doc_path'],
                    ]);
                }
                if($doc['id']!=null)
                {
                    GruzootpravitelFile::where('id', '=',$doc['id'])->update([
                        'file_name' => $doc['file_name'],
                    ]);
                }

            }
        }
        //если массив приходящий пустой но там в БД лежали старые файлы, то их нужно удалить
        else
        {
            $old_docs=GruzootpravitelFile::where('gruzootpravitel_id', '=',$current_gruzootpravitel_id)->get();
            foreach ($old_docs as $old_doc)
            {
                try {
                    $path_to_del = public_path() . "/modal/" . $old_doc['server_name'];
                    unlink($path_to_del);
                    GruzootpravitelFile::where('id', '=',$old_doc['id'])->delete();
                }
                catch (\Throwable $e)
                {

                }
            }
        }

    }

}
