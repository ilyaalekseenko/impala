<?php

namespace Database\Seeders;

use App\Models\TemplateVar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateVarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr_to_ins=[
            ['data_pogruzki','1','Дата погрузки'],
            ['por_nomer','1','Порядковый номер ТС'],
            ['adres_pogruzki','1','Адрес погрузки'],
            ['adres_vygruzki','1','Адрес выгрузки'],
            ['kol_gruzomest','1','Количество грузомест'],
            ['data_dostavki','1','Дата доставки'],
            ['gruzootpravitel','1','Грузоотправитель'],
            ['mesto_pogruzki','1','Место погрузки'],
            ['voditel','1','ФИО водителя'],
            ['data_vydachi','2','дата выдачи'],
            ['srok_deist','2','срок действия'],
            ['voditel','2','водитель'],
            ['postav','2','поставщик'],
            ['organizacia','2','организация'],
            ['vod_passport','2','серия паспорта водителя'],
            ['vod_passport_kem','2','кем выдан'],
            ['vod_passport_kogda','2','когда выдан выдан'],
            ['test3','3','ФИО водителя3'],
        ];
        foreach ($arr_to_ins as $loc)
        {
            TemplateVar::create([
                'var_name' =>$loc[0],
                'doc_type' =>$loc[1],
                'description' =>$loc[2],
            ]);
        }
    }
}
