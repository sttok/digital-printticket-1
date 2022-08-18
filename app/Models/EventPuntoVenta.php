<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class EventPuntoVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'punto_id',
    ];

    public function  puntoventa()
    {
        return $this->hasOne(User::class, 'id', 'punto_id');
    }

    public function evento()
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }
}
