<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'image',
        'working_days',
        'working_hours_start',
        'working_hours_end',
        'reservation_intervals',
        'price', 
    ];
    

    public function generateTimeSlots()
    {
        $start = Carbon::parse($this->working_hours_start);
        $end = Carbon::parse($this->working_hours_end);
        $interval = Carbon::parse($this->reservation_intervals);
    
        $timeSlots = [];
        while ($start->lt($end)) {
            $timeSlots[] = $start->format('H:i');
            $start->addHours($interval->hour)->addMinutes($interval->minute);
        }
    
        return $timeSlots;
    }
}


