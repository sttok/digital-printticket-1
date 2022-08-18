<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoAdicionalEscarapelas extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'escarapela_id',
        'nombres',
        'cedula',
        'zona',
        'imagen'
    ];
}
