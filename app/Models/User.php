<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con las reservas que le pertenecen como propietario del servicio.
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'propietario_id'); // 'propietario_id' debe coincidir con tu tabla
    }

    /**
     * Relación con las reservas que el usuario ha realizado.
     */
    public function reservasRealizadas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id'); // 'usuario_id' debe coincidir con tu tabla
    }
}
