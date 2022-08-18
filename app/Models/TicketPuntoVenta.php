<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPuntoVenta extends Model
{
    use HasFactory;

    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'id', 'ticket_id');
    }
}
