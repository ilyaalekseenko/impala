<?php
namespace App\Traits;
trait DeleteTrait
{
    //перебираем старые данные и сравниваем их с новыми, если нету то удаляем
    //$old массив старых данных
    //$new массив новых данных
    public function deleteFromDB($old,$new,$model)
    {
        $model = 'App\Models\\' . $model;
        foreach($old as $oneOld)
        {
            $flag=false;
            foreach ($new as $oneNew)
            {
                if($oneNew['id']!=null)
                {
                    if($oneOld['id']==$oneNew['id'])
                    {
                        $flag=true;
                    }
                }
            }
            if(!$flag)
            {
                $model::where('id', $oneOld['id'])->delete();
            }
        }
    }
}
