<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalOrdenCompraAnulado extends Model
{
    use HasFactory;
    
    public function usuario(){
         return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function organizador(){
        return $this->hasOne(User::class, 'id', 'organizador_id');
    }
    
    public function digitalordencompra(){
         return $this->hasOne(DigitalOrdenCompra::class, 'id', 'compra_id');
    }
}

