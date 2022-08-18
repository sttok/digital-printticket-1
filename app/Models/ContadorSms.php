<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContadorSms extends Model
{
    use HasFactory;

    public function Usuario(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
