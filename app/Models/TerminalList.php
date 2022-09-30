<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminalList extends Model
{
    use HasFactory;
    protected $table = 'terminal';
    protected $fillable = ['id','order_id','terminal_TS','id_ts'];
}
