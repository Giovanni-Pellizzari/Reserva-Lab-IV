<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $user = Auth::user();
        $services = Service::when($user, function($query) use ($user) {
            return $query->where('user_id', '!=', $user->id);
        })->get();
    
        foreach ($services as $service) {
            $start = \Carbon\Carbon::parse($service->working_hours_start);
            $end = \Carbon\Carbon::parse($service->working_hours_end);
            $interval = (int) $service->reservation_intervals;
    
            if ($interval <= 0 || $start->gte($end)) {
                continue; 
            }
    
            $slots = [];
            while ($start->lt($end)) {
                $slots[] = $start->format('H:i');
                $start->addMinutes($interval);
            }
    
            $service->available_slots = $slots;
        }
    
        return view('home', ['services' => $services]);
    }
}