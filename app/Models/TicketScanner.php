<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketScanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'scanner_id',
        'ticket_id'
    ];
    
   public function ticket()
    {
        return $this->hasOne(Ticket::class, 'id', 'ticket_id');
    }
}
