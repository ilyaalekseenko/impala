<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsList extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'docs_list';

        public function getDocsList()
        {

        }

        public function createNewList($id)
        {
            $doc= DocsList::create([]);
            return $doc;
        }
        public function updateList($id,$list_name)
        {
           return DocsList::where('id',$id)->update([
                'list_name' =>$list_name,
            ]);
        }
        public function deleteList($id)
        {
            DocsList::where('id',$id)->delete();
        }

}
