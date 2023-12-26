<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impala extends Model
{
    use HasFactory;
    protected $guarded = false;
    public function getAdres()
    {
        return Impala::get();
    }
    public function updateAdres($adres)
    {
        Impala::updateOrCreate(
            ['id' => 1],
            ['adres' => $adres]
        );
    }
}
