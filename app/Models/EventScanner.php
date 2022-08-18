<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventScanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'scanner_id',
    ];

    public function scanner(){

        return $this->hasOne(User::class, 'id', 'scanner_id');
    }
}
