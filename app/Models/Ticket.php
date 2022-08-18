<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_number',
        'name',
        'type',
        'quantity',
        'ticket_per_order',
        'start_time',
        'end_time',
        'price',
        'description',
        'categoria',
        'tipo',
        'status',
        'generadas',
        'is_deleted',
        'identificador',
        'forma_generar',
        'palcos',
        'puestos',
        'puestos_adicionales',
        'privacidad',
        'precio_preventa'
    ];

    protected $table = 'tickets';
    protected $dates = ['start_time', 'end_time'];


    public function evento(){
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function keyevento(){
        return $this->hasOne(EventoKeyToken::class, 'event_id', 'event_id');
    }
}
