<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalOrdenCompra extends Model
{
    use HasFactory;

    public function cliente(){
        return $this->hasOne(AppUser::class, 'id', 'cliente_id');
    }
}
