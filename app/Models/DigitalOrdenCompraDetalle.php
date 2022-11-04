<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalOrdenCompraDetalle extends Model
{
    use HasFactory;

    public function digital(){
        return $this->hasOne(OrderChildsDigital::class, 'id', 'digital_id');
    }

    public function entrada(){
        return $this->hasOne(OrderChild::class, 'id', 'order_child_id');
    }

    public function endosado(){
        return $this->hasOne(Appuser::class, 'id', 'endosado_id');
    }
}
