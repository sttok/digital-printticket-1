<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderChild extends Model 
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'customer_id',
        'endosado_id',
        'vendedor_id',
        'ticket_id',
        'ticket_number',
        'ticket_number2',
        'ticket_hash',
        'ticket_hash2',
        'ticket_hash3',
        'ticket_hash4',
        'consecutivo',
        'salto',
        'identificador',
        'status',
        'mesas', 'asiento'
    ];

    protected $table = 'order_child';
   // protected $appends = ['orderData'];

    // public function getOrderDataAttribute()
    // {
    //     $order  = Order::with(['customer:id,name,last_name,email,image']);
    // }

    public function evento()
    {
        return $this->hasOne(Ticket::class, 'id', 'ticket_id');
    }

    public function orden()
    {
        return $this->hasOne('App\Models\Order', 'id', 'order_id');
    }

    public function cliente()
    {
        return $this->hasOne(AppUser::class, 'id', 'customer_id');
    }

    public function endosado()
    {
        return $this->hasOne(AppUser::class, 'id', 'endosado_id');
    }
    
    public function infoescarapela()
    {
        return $this->hasOne(InfoAdicionalEscarapelas::class, 'id', 'escarapela_id');
    }

  
}
