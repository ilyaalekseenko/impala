<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogistName extends Model
{
    use HasFactory;
    protected $table = 'logist_name';
    protected $fillable = ['id','logist_name' ];
}
