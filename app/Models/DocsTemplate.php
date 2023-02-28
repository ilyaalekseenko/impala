<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsTemplate extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getFirstInModel($field, $data)
    {
       return DocsTemplate::where($field, $data)->first();
    }
    public function updateOneFieldInModel($id,$field,$data)
    {
        DocsTemplate::where('id', $id)->update([
            $field =>$data,
        ]);
    }
    public function createDocFieldsInModel($doc_type,$doc_name)
    {
        DocsTemplate::create(
            [
                'doc_type' =>$doc_type,
                'doc_name' =>$doc_name,
            ]
        );
    }

}
