<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'availability_date',
        'start_time',
        'end_time',
        'is_available'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
