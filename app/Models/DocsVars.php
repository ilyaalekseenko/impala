<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsVars extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'docs_vars';

    public function getDocVarsByType($type)
    {
        return DocsVars::join('docs_list','docs_vars.list_id','=','docs_list.id')->select('docs_vars.*','docs_list.list_name') ->get();
    }
    public function deleteCellByListId($id)
    {
        DocsVars::where('list_id',$id)->delete();
    }
    public function createNewCell($id,$list_id)
    {
        return DocsVars::create([
            'list_id' =>$list_id,
            'doc_type' =>$id,
        ]);
    }
    public function updateList($id,$cell_number)
    {
        return DocsVars::where('id',$id)->update([
            'cell_number' =>$cell_number,
        ]);
    }
    public function updateCellByName($id,$cellVar,$cellName)
    {
        return DocsVars::where('id',$id)->update([
            $cellName =>$cellVar,
        ]);
    }
    public function deleteCell($id)
    {
        DocsVars::where('id',$id)->delete();
    }
}
