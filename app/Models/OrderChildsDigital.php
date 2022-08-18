<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderChildsDigital extends Model
{
    use HasFactory;

    public function entrada(){
        return $this->hasOne(OrderChild::class, 'id', 'order_child_id');
    }

    public function zona(){
        return $this->hasOne(Ticket::class, 'id', 'zona_id');
    }
}
