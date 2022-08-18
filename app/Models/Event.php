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
    protected $appends = ['imagePath', 'totalTickets', 'soldTickets'];

    public function user()
    {
        return $this->belongsTo(AppUser::class);
    }

    public function categoria()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
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

    public function scanner()
    {
        return $this->hasMany(EventScanner::class, 'event_id', 'id');
    }

    public function contador()
    {
        return $this->belongsTo(EventContadorLikes::class, 'id', 'event_id');
    }

    public function appuser1()
    {
        return $this->hasMany(EventAppuserLikes::class, 'event_id', 'id');
    }

    public function getTotalTicketsAttribute()
    {
        $timezone = Setting::find(1)->timezone;
        $date = Carbon::now($timezone);
        return Ticket::where([['event_id', $this->attributes['id']], ['is_deleted', 0], ['status', 1], ['end_time', '>=', $date->format('Y-m-d H:i:s')], ['start_time', '<=', $date->format('Y-m-d H:i:s')]])->sum('quantity');
    }

    public function getSoldTicketsAttribute()
    {
        return  Order::where('event_id', $this->attributes['id'])->sum('quantity');
    }

   

    public function scopeDurationData($query, $start, $end)
    {
        $data =  $query->whereBetween('start_time', [$start,  $end]);
        return $data;
    }

    public function asignadoscanner($user)
    {
        if ($this->eventscanner()->where('scanner_id', $user->id)->first()) {
            return true;
        }
        return false;
    }

    public function eventscanner()
    {
        return $this->hasMany(EventScanner::class);
    }

    public function contadorscanner()
    {
        return $this->hasMany(EventScanner::class, 'event_id');
    }

    public function asignadopuntoventa($user)
    {
        if ($this->eventpuntoventa()->where('punto_id', $user->id)->get()) {
            return true;
        }
        return false;
    }

    public function eventpuntoventa()
    {
        return $this->hasMany(EventPuntoVenta::class);
    }

    public function puntodeventa()
    {
        return $this->hasMany(User::class, 'id');
    }

    public function Contadorestadisticas(){
        return $this->hasOne(ContadorVisitaEventoDetalle::class, 'evento_id');
    }
}
