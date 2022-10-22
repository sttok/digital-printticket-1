<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'organizador_id',
        'type',
        'address',
        'ciudad',
        'pais',
        'category_id',
        'start_time',
        'end_time',
        'image',
        'imagen_banner',
        'imagen_mapa',
        'people',
        'google_iframe',
        'description',
        'descripcion_corta',
        'security',
        'status',
        'event_status',
        'is_deleted',
        'tags',
        'plataforma',
        'url_evento',
        'ubicacion_id'
    ];

    protected $table = 'events';
    protected $dates = ['start_time', 'end_time'];  

    public function user()
    {
        return $this->belongsTo(AppUser::class);
    }

    public function categoria()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    
    public function Ciudad()
    {
        return $this->hasOne(ciudades::class, 'id', 'ciudad');
    }

    public function Pais()
    {
        return $this->hasOne(pais::class, 'Codigo', 'pais');
    }

    public function organizador()
    {
        return $this->hasOne(User::class, 'id', 'organizador_id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'event_id', 'id');
    }

    public function ticket_digital()
    {
        return $this->hasMany(Ticket::class, 'event_id', 'id')->where([
            ['tipo', 1], ['categoria', 2], ['status', 1], ['is_deleted', 0]
        ]);
    }
   
   

    public function appuser1()
    {
        return $this->hasMany(EventAppuserLikes::class, 'event_id', 'id');
    }

    public function eventpuntoventa()
    {
        return $this->hasMany(EventPuntoVenta::class);
    }

    public function puntodeventa()
    {
        return $this->hasMany(User::class, 'id');
    }

   
}
